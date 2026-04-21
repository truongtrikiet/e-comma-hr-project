<?php

namespace App\Services;

use App\Repositories\AIProfile\AIProfileRepositoryInterface;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use App\Enum\ActiveStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class AIProfileService
{
    public function __construct(
        protected AIProfileRepositoryInterface $aIProfileRepository,
    ) {
        //
    }

    /**
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['status'] = ActiveStatus::ACTIVE->value;

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? null);

            $aIProfile = $this->aIProfileRepository->create($data);

            DB::commit();

            return $aIProfile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Override update method.
     */
    public function update($aIProfile, $data)
    {
        try {
            DB::beginTransaction();

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? $aIProfile->school_id);

            $updatedModel = $this->aIProfileRepository->update($aIProfile, $data);

            DB::commit();

            return $updatedModel;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Test connection API
     */
    public function testApiConnection($aIProfile)
    {
        try {
            $profileModel = is_numeric($aIProfile) ? $this->aIProfileRepository->find($aIProfile) : $aIProfile;

            $endpoint = rtrim($profileModel->endpoint ?? config('ai.endpoint'), '/');
            $model = $profileModel->model ?? config('ai.model');

            $apiKey = null;
            if (!empty($profileModel->api_key_encrypted)) {
                try {
                    $apiKey = Crypt::decryptString($profileModel->api_key_encrypted);
                } catch (\Throwable $e) {
                    $apiKey = $profileModel->api_key_encrypted;
                }
            }
            $apiKey = $apiKey ?? config('ai.api_key');

            $payload = [
                'contents' => [
                    [
                    'role' => 'user',
                    'parts' => [
                        ['text' => 'Test connection from ' . config('app.name', 'application')],
                    ],
                    ],
                ],
                'generationConfig' => [
                    'max_output_tokens' => 10,
                    'temperature' => 0.0,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->timeout(config('ai.timeout'))
            ->post($endpoint . '/' . $model . ':generateContent?key=' . $apiKey, $payload);

            $result = $response->json();

            return $result;
        } catch (\Exception $e) {
            Log::info('Test API connection failed for AI Profile ID: ' . $aIProfile->id . ' with error: ' . $e->getMessage());
            throw $e;
        }
    }
}
