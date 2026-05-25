<?php

namespace App\Services;

use App\Models\AIProfile;
use App\Models\CandidateScreening;
use App\Enum\CandidateScreeningStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidateScreeningResultMail;
use Carbon\Carbon;

class CandidateScreeningService
{
    public function __construct(
        protected AIService $aiService,
        protected ResumeTextService $resumeTextService,
    ) {
        //
    }

    public function scanAndAnalyze(
        int $schoolId,
        int $aiProfileId,
        string $positionType,
        array $files
    ): array {
        $profile = AIProfile::findOrFail($aiProfileId);

        $stats = [
            'created' => 0,
            'passed' => 0,
            'failed' => 0,
        ];
        $errors = [];

        $batch = [];
        $batchSize = (int) config('ai.batch_size', 10);

        if (count($files) > $batchSize) {
            foreach (array_slice($files, $batchSize) as $file) {
                $errors[] = [
                    'file' => method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null,
                    'message' => "Maximum {$batchSize} CVs can be scanned in one batch.",
                    'code' => null,
                ];
            }
        }

        foreach (array_slice($files, 0, $batchSize) as $index => $file) {
            try {
                $batch[] = $this->prepareResumeForBatch($file, $index);
            } catch (Throwable $e) {
                $fname = method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : null;
                Log::error('[AI SCAN] file failed', [
                    'file'  => $fname,
                    'error' => $e->getMessage(),
                ]);

                $errors[] = [
                    'file' => $fname,
                    'message' => $e->getMessage(),
                    'code' => $e->getCode() ?? null,
                ];
            }
        }

        if (empty($batch)) {
            return array_merge($stats, ['errors' => $errors]);
        }

        try {
            $aiResult = $this->aiService->scanResumes(
                profile: $profile,
                resumes: array_map(fn (array $item): array => [
                    'cv_id' => $item['cv_id'],
                    'cv_text' => $item['resume_text'],
                ], $batch),
                positionType: $positionType
            );

            $this->throwIfProviderError($aiResult);

            if (!isset($aiResult['results']) || !is_array($aiResult['results'])) {
                throw new RuntimeException($aiResult['reason'] ?? 'AI batch result missing results');
            }

            $resultsByCvId = collect($aiResult['results'])->keyBy('cv_id');

            foreach ($batch as $item) {
                try {
                    $result = $resultsByCvId->get($item['cv_id']);

                    if (!is_array($result)) {
                        throw new RuntimeException('AI result missing for ' . $item['cv_id']);
                    }

                    $this->createScreeningRecord(
                        item: $item,
                        schoolId: $schoolId,
                        profile: $profile,
                        positionType: $positionType,
                        aiResult: $result,
                        stats: $stats
                    );
                } catch (Throwable $e) {
                    Log::error('[AI SCAN] result failed', [
                        'file' => $item['file_name'],
                        'cv_id' => $item['cv_id'],
                        'error' => $e->getMessage(),
                    ]);

                    $errors[] = [
                        'file' => $item['file_name'],
                        'message' => $e->getMessage(),
                        'code' => $e->getCode() ?: null,
                    ];
                }
            }
        } catch (Throwable $e) {
            Log::error('[AI SCAN] batch failed', [
                'file_count' => count($batch),
                'error' => $e->getMessage(),
            ]);

            foreach ($batch as $item) {
                $errors[] = [
                    'file' => $item['file_name'],
                    'message' => $e->getMessage(),
                    'code' => $e->getCode() ?: null,
                ];
            }
        }

        return array_merge($stats, ['errors' => $errors]);
    }

    private function prepareResumeForBatch($file, int $index): array
    {
        Log::info('[AI SCAN] prepare file', [
            'file' => $file->getClientOriginalName(),
        ]);

        $path = $file->store('resumes', 'public');
        $resumeText = $this->resumeTextService->extract($file);

        if (trim($resumeText) === '') {
            throw new RuntimeException('Resume text is empty');
        }

        return [
            'cv_id' => 'cv_' . ($index + 1),
            'file_name' => $file->getClientOriginalName(),
            'resume_file_path' => $path,
            'resume_text' => $resumeText,
        ];
    }

    private function createScreeningRecord(
        array $item,
        int $schoolId,
        AIProfile $profile,
        string $positionType,
        array $aiResult,
        array &$stats
    ): void {
        DB::transaction(function () use (
            $item,
            $schoolId,
            $profile,
            $positionType,
            $aiResult,
            &$stats
        ) {
            Log::info('[AI SCAN] AI result', [
                'file' => $item['file_name'],
                'cv_id' => $item['cv_id'],
                'keys' => array_keys($aiResult),
            ]);

            $isSuitable = $this->normalizeIsSuitable($aiResult);
            $candidate = data_get($aiResult, 'candidate', []);
            $candidateName = data_get($candidate, 'name');
            $candidateEmail = data_get($candidate, 'email');
            $candidatePhone = data_get($candidate, 'phone') ?? data_get($candidate, 'phone_number');

            if (empty($candidateName) && empty($candidateEmail) && empty($candidatePhone)) {
                Log::warning('[AI SCAN] candidate info missing or empty', [
                    'file' => $item['file_name'],
                    'cv_id' => $item['cv_id'],
                    'ai_keys' => array_keys($aiResult),
                ]);
            }

            $record = CandidateScreening::create([
                'school_id' => $schoolId,
                'ai_profile_id' => $profile->id,
                'position_type' => $positionType,
                'resume_file_path' => $item['resume_file_path'],
                'candidate_name' => $candidateName,
                'candidate_email' => $candidateEmail,
                'candidate_phone_number' => $candidatePhone,
                'ai_result_json' => $aiResult,
                'recommended_roles' => data_get($aiResult, 'recommended_roles', []),
                'is_suitable' => $isSuitable,
                'status' => $isSuitable
                    ? CandidateScreeningStatus::PASSED
                    : CandidateScreeningStatus::FAILED,
                'screened_at' => now(),
            ]);

            Log::info('[AI SCAN] created', [
                'id' => $record->id,
                'file' => $item['file_name'],
                'cv_id' => $item['cv_id'],
                'is_suitable' => $isSuitable,
            ]);

            $stats['created']++;
            $isSuitable ? $stats['passed']++ : $stats['failed']++;
        });
    }

    private function throwIfProviderError(array $aiResult): void
    {
        if (isset($aiResult['raw']) && is_string($aiResult['raw'])) {
            $decodedRaw = json_decode($aiResult['raw'], true);

            if (json_last_error() === JSON_ERROR_NONE && isset($decodedRaw['error'])) {
                $err = $decodedRaw['error'];
                $message = $err['message'] ?? json_encode($err);
                $code = $err['code'] ?? 0;

                throw new RuntimeException(sprintf('Provider error %s: %s', $code, $message), (int) $code);
            }
        }

        if (isset($aiResult['reason']) && !isset($aiResult['results'])) {
            throw new RuntimeException((string) $aiResult['reason']);
        }
    }

    private function normalizeIsSuitable(array $aiResult): bool
    {
        if (! array_key_exists('is_suitable', $aiResult)) {
            throw new RuntimeException('AI result missing is_suitable');
        }

        $value = $aiResult['is_suitable'];

        if (is_bool($value)) {
            return $value;
        }

        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (! is_bool($bool)) {
            throw new RuntimeException('Invalid is_suitable value');
        }

        return $bool;
    }

    /**
     * Delete all by status filter.
     */
    public function deleteAllByStatus($status)
    {
        return CandidateScreening::where('status', $status)->delete();
    }

    /**
     * Send candidate screening result to email.
     */
    public function sendResultEmail(CandidateScreening $candidateScreening, array $interviewData)
    {
        if (!$candidateScreening->candidate_email) {
            throw new \Exception('Candidate email not found.');
        }

        $interview = [];
        if (!empty($interviewData['interview_time'])) {
            try {
                $interview['time'] = Carbon::parse($interviewData['interview_time'])->format('Y-m-d H:i');
            } catch (\Throwable $e) {
                $interview['time'] = (string) ($interviewData['interview_time']);
            }
        }

        $interview['location'] = $interviewData['interview_location'] ?? null;
        $interview['note'] = $interviewData['interview_note'] ?? null;

        Mail::to($candidateScreening->candidate_email)
            ->send(new CandidateScreeningResultMail($candidateScreening, $interview));
    }
}
