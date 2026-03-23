<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\ResetTypeEnum;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use App\Repositories\FurloughPolicy\FurloughPolicyRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\FurloughBalance;
use App\Models\User;

class FurloughPolicyService
{
    public function __construct(
        protected FurloughPolicyRepositoryInterface $furloughPolicyRepository,
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

            $furloughPolicy = $this->furloughPolicyRepository->create($data);

            if (!empty($data['employee_type_id'])) {
                $this->generateBalancesForUsers($furloughPolicy);
            }

            DB::commit();

            return $furloughPolicy;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating furlough policy: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Override update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            if ($data['reset_type'] == ResetTypeEnum::YEARLY->value || $data['reset_type'] == ResetTypeEnum::NONE->value) {
                $data['reset_month'] = null;
            }

            $this->furloughPolicyRepository->update($model, $data);

            if (isset($data['employee_type_id'])) {
                $this->generateBalancesForUsers($model);
            }

            DB::commit();

            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating furlough policy: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function generateBalancesForUsers($policy): void
    {
        $users = User::where('school_id', $policy->school_id)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where('user_profiles.employee_type_id', $policy->employee_type_id)
            ->select('users.*')
            ->get();

        foreach ($users as $user) {
            try {
                FurloughBalance::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'furlough_type_id' => $policy->furlough_type_id,
                    ],
                    [
                        'total_days' => $policy->max_days ?? 0,
                        'used_days' => 0,
                        'remaining_days' => 0,
                    ]
                );
            } catch (\Throwable $e) {}
        }
    }
}
