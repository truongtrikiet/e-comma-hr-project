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

        foreach ($files as $file) {
            try {
                $this->processOneFile(
                    file: $file,
                    schoolId: $schoolId,
                    profile: $profile,
                    positionType: $positionType,
                    stats: $stats
                );
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

        return array_merge($stats, ['errors' => $errors]);
    }

    private function processOneFile(
        $file,
        int $schoolId,
        AIProfile $profile,
        string $positionType,
        array &$stats
    ): void {
        DB::transaction(function () use (
            $file,
            $schoolId,
            $profile,
            $positionType,
            &$stats
        ) {
            Log::info('[AI SCAN] start file', [
                'file' => $file->getClientOriginalName(),
            ]);

            $path = $file->store('resumes', 'public');
            $resumeText = $this->resumeTextService->extract($file);

            if (trim($resumeText) === '') {
                throw new RuntimeException('Resume text is empty');
            }

            $aiResult = $this->aiService->scanResume(
                profile: $profile,
                resumeText: $resumeText,
                positionType: $positionType
            );

            if (isset($aiResult['raw']) && is_string($aiResult['raw'])) {
                $decodedRaw = json_decode($aiResult['raw'], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decodedRaw['error'])) {
                    $err = $decodedRaw['error'];
                    $message = $err['message'] ?? json_encode($err);
                    $code = $err['code'] ?? 0;
                    throw new RuntimeException(sprintf('Provider error %s: %s', $code, $message), (int)$code);
                }
            }

            Log::info('[AI SCAN] AI result', [
                'file' => $file->getClientOriginalName(),
                'keys' => array_keys($aiResult),
            ]);

            $isSuitable = $this->normalizeIsSuitable($aiResult);
                $candidate = data_get($aiResult, 'candidate', []);
                $candidateName = data_get($candidate, 'name');
                $candidateEmail = data_get($candidate, 'email');
                $candidatePhone = data_get($candidate, 'phone') ?? data_get($candidate, 'phone_number');

                if (empty($candidateName) && empty($candidateEmail) && empty($candidatePhone)) {
                    Log::warning('[AI SCAN] candidate info missing or empty', [
                        'file' => $file->getClientOriginalName(),
                        'ai_keys' => array_keys($aiResult),
                    ]);
                }

            $record = CandidateScreening::create([
                'school_id' => $schoolId,
                'ai_profile_id' => $profile->id,
                'position_type' => $positionType,
                'resume_file_path' => $path,
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
                'is_suitable' => $isSuitable,
            ]);

            $stats['created']++;
            $isSuitable ? $stats['passed']++ : $stats['failed']++;
        });
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
