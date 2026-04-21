<?php

namespace App\Providers\AI;

use App\Models\AIProfile;
use Illuminate\Support\Facades\Http;

class OpenAIProvider implements AIProviderInterface
{
    public function scan(AIProfile $aiProfile, string $prompt): array
    {
        $endpoint = rtrim($aiProfile->endpoint, '/');
        $apiKey = decrypt($aiProfile->api_key_encrypted);

        $url = "{$endpoint}/{$aiProfile->model}:generateContent?key={$apiKey}";

        $response = Http::timeout(config('ai.timeout', 60))
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                ],
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Gemini API error: ' . $response->body()
            );
        }

        return $this->parseResponse($response->json());
    }

    protected function parseResponse(array $response): array
    {
        $text = data_get(
            $response,
            'candidates.0.content.parts.0.text'
        );

        if (!$text) {
            return [
                'is_suitable' => false,
                'reason' => 'Empty Gemini response',
                'raw' => $response,
            ];
        }

        $decoded = json_decode($text, true);

        return is_array($decoded)
            ? $decoded
            : [
                'is_suitable' => false,
                'reason' => 'Invalid JSON returned by Gemini',
                'raw' => $text,
            ];
    }
}
