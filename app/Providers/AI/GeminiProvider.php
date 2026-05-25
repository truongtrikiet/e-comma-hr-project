<?php

namespace App\Providers\AI;

use App\Models\AIProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    public function scan(AIProfile $aiProfile, string $prompt): array
    {
        $apiKey = null;
        try {
            $apiKey = decrypt($aiProfile->api_key_encrypted);
        } catch (\Throwable $e) {
            $apiKey = $aiProfile->api_key_encrypted;
        }

        $url = rtrim($aiProfile->endpoint, '/') . '/' . $aiProfile->model . ':generateContent?key=' . $apiKey;

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.0,
                'maxOutputTokens' => config('ai.max_output_tokens', 4096),
                'responseMimeType' => 'application/json',
            ],
        ];

        Log::info('[GEMINI REQUEST]', [
            'profile_id' => $aiProfile->id,
            'endpoint' => rtrim($aiProfile->endpoint, '/'),
            'model' => $aiProfile->model,
            'payload_preview' => mb_substr(json_encode($payload), 0, 1000),
        ]);

        $response = Http::timeout(config('ai.timeout', 60))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        if (! $response->successful()) {
            Log::error('[GEMINI ERROR]', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'is_suitable' => false,
                'reason' => 'Gemini request failed',
                'raw' => $response->body(),
            ];
        }

        $json = $response->json();

        $finishReason = data_get($json, 'candidates.0.finishReason');
        if ($finishReason === 'MAX_TOKENS') {
            Log::warning('[GEMINI TRUNCATED]', [
                'profile_id' => $aiProfile->id,
                'model' => $aiProfile->model,
                'finishReason' => $finishReason,
            ]);

            return [
                'is_suitable' => false,
                'reason' => 'Gemini response truncated (MAX_TOKENS). Try increasing maxOutputTokens or shorten the prompt.',
                'raw' => $json,
            ];
        }

        return $this->parseResponse($json);
    }

    protected function parseResponse(array|string $response): array
    {
        if (is_string($response)) {
            $text = $response;
        } else {
            $text = data_get($response, 'candidates.0.content.parts.0.text');
        }

        if (! $text) {
            Log::warning('[GEMINI PARSE] empty text in response', ['preview' => mb_substr(json_encode($response), 0, 500)]);

            return [
                'is_suitable' => false,
                'reason' => 'Empty Gemini response',
                'raw' => $response,
            ];
        }

        Log::info('[GEMINI RAW TEXT]', [
            'preview' => mb_substr($text, 0, 500),
        ]);

        $jsonText = $this->extractJsonText($text);
        $decoded = json_decode($jsonText, true);

        if (! is_array($decoded)) {
            $jsonError = json_last_error_msg();

            Log::warning('[GEMINI PARSE] invalid JSON', [
                'error' => $jsonError,
                'raw' => $text,
                'cleaned_preview' => mb_substr($jsonText, 0, 500),
            ]);

            return [
                'is_suitable' => false,
                'reason' => 'Invalid JSON from Gemini: ' . $jsonError,
                'raw' => $text,
            ];
        }

        return $decoded;
    }

    private function extractJsonText(string $text): string
    {
        $text = trim($text);

        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $text, $matches)) {
            $text = trim($matches[1]);
        }

        if (str_starts_with($text, '{') || str_starts_with($text, '[')) {
            return $text;
        }

        $objectStart = strpos($text, '{');
        $arrayStart = strpos($text, '[');

        $starts = array_filter([$objectStart, $arrayStart], fn ($position) => $position !== false);
        if (empty($starts)) {
            return $text;
        }

        $start = min($starts);
        $end = strrpos($text, str_starts_with(substr($text, $start), '{') ? '}' : ']');

        return $end === false
            ? $text
            : trim(substr($text, $start, $end - $start + 1));
    }
}
