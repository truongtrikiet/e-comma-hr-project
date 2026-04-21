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
            // fall back to raw value
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
            ],
        ];

        Log::info('[GEMINI REQUEST]', [
            'profile_id' => $aiProfile->id,
            'url' => $url,
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

        return $this->parseResponse($response->json());
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

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            Log::warning('[GEMINI PARSE] invalid JSON', ['error' => json_last_error_msg(), 'raw' => $text]);

            return [
                'is_suitable' => false,
                'reason' => 'Invalid JSON from Gemini: ' . json_last_error_msg(),
                'raw' => $text,
            ];
        }

        return $decoded;
    }
}
