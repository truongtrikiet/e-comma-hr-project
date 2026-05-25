<?php

namespace App\Services;

use App\Enum\AIProviderEnum;
use App\Models\AIProfile;
use App\Providers\AI\GeminiProvider;
use App\Providers\AI\OpenAIProvider;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class AIService
{
    public function scanResume(
        AIProfile $profile,
        string $resumeText,
        string $positionType
    ): array {
        $batchResult = $this->scanResumes(
            profile: $profile,
            resumes: [
                [
                    'cv_id' => 'cv_1',
                    'cv_text' => $resumeText,
                ],
            ],
            positionType: $positionType
        );

        return data_get($batchResult, 'results.0', $this->fail('AI scan result missing'));
    }

    public function scanResumes(
        AIProfile $profile,
        array $resumes,
        string $positionType
    ): array {
        if (empty($resumes)) {
            return [
                'position_type' => $positionType,
                'results' => [],
            ];
        }

        $promptTemplate = config('ai.prompts.cv_screening_batch');

        if (!$promptTemplate) {
            throw new RuntimeException('AI batch prompt not configured');
        }

        $batch = array_map(function (array $resume): array {
            return [
                'cv_id' => (string) ($resume['cv_id'] ?? ''),
                'cv_text' => trim((string) ($resume['cv_text'] ?? $resume['text'] ?? '')),
            ];
        }, $resumes);

        $cvBatchJson = json_encode(
            $batch,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
        );

        $prompt = str_replace(
            ['{{position_type}}', '{{cv_batch_json}}'],
            [$positionType, $cvBatchJson],
            $promptTemplate
        );

        Log::info('[AI] batch scan start', [
            'profile_id' => $profile->id,
            'provider' => $profile->provider?->value,
            'model' => $profile->model,
            'cv_count' => count($batch),
            'text_len' => array_sum(array_map(fn (array $item): int => strlen($item['cv_text']), $batch)),
        ]);

        $result = $this->scanWithPrompt($profile, $prompt);

        return $this->normalizeBatchResult($result, $resumes, $positionType);
    }

    private function scanWithPrompt(AIProfile $profile, string $prompt): array
    {
        try {
            return match ($profile->provider?->value) {
                AIProviderEnum::GEMINI->value =>
                    app(GeminiProvider::class)->scan($profile, $prompt),

                AIProviderEnum::OPENAI->value =>
                    app(OpenAIProvider::class)->scan($profile, $prompt),

                default =>
                    $this->fail('Unsupported AI provider'),
            };
        } catch (Throwable $e) {
            Log::error('[AI] scan error', [
                'profile_id' => $profile->id,
                'provider' => $profile->provider?->value,
                'error' => $e->getMessage(),
            ]);

            return $this->fail('AI scan exception', $e->getMessage());
        }
    }

    private function normalizeBatchResult(array $result, array $resumes, string $positionType): array
    {
        if (isset($result['results']) && is_array($result['results'])) {
            $result['position_type'] ??= $positionType;
            $result['results'] = $this->normalizeBatchItems($result['results'], $resumes, $positionType);

            return $result;
        }

        if (array_is_list($result)) {
            return [
                'position_type' => $positionType,
                'results' => $this->normalizeBatchItems($result, $resumes, $positionType),
            ];
        }

        if (count($resumes) === 1 && array_key_exists('is_suitable', $result)) {
            return [
                'position_type' => $positionType,
                'results' => [
                    array_merge(
                        [
                            'cv_id' => (string) ($resumes[0]['cv_id'] ?? 'cv_1'),
                            'position_type' => $positionType,
                        ],
                        $result
                    ),
                ],
            ];
        }

        return $result;
    }

    private function normalizeBatchItems(array $items, array $resumes, string $positionType): array
    {
        $normalized = [];

        foreach (array_values($items) as $index => $item) {
            $item = is_array($item) ? $item : [];
            $item['cv_id'] = (string) ($item['cv_id'] ?? $resumes[$index]['cv_id'] ?? 'cv_' . ($index + 1));
            $item['position_type'] = $item['position_type'] ?? $positionType;

            $normalized[] = $item;
        }

        return $normalized;
    }

    private function fail(string $reason, ?string $error = null): array
    {
        return [
            'is_suitable' => false,
            'reason' => $reason,
            'error' => $error,
        ];
    }
}
