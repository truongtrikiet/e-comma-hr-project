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
        $promptTemplate = config('ai.prompts.cv_screening');

        if (!$promptTemplate) {
            throw new RuntimeException('AI prompt not configured');
        }

        $prompt = str_replace(
            ['{{position_type}}', '{{cv_text}}'],
            [$positionType, trim($resumeText)],
            $promptTemplate
        );

        Log::info('[AI] scan start', [
            'profile_id' => $profile->id,
            'provider' => $profile->provider?->value,
            'model' => $profile->model,
            'text_len' => strlen($resumeText),
        ]);

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

    private function fail(string $reason, ?string $error = null): array
    {
        return [
            'is_suitable' => false,
            'reason' => $reason,
            'error' => $error,
        ];
    }
}
