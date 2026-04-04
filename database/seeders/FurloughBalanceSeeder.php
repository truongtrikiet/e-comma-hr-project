<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FurloughPolicy;
use App\Models\FurloughBalance;
use Illuminate\Support\Facades\Log;

class FurloughBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('userProfile')->get();

        foreach ($users as $user) {
            $employeeTypeId = $user->userProfile->employee_type_id ?? null;
            $schoolId = $user->school_id ?? null;

            if (! $employeeTypeId || ! $schoolId) {
                continue;
            }

            $policies = FurloughPolicy::where('school_id', $schoolId)
                ->where('employee_type_id', $employeeTypeId)
                ->get();

            foreach ($policies as $policy) {
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
                            'last_accrual_at' => null,
                            'last_reset_at' => null,
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::error('FurloughBalanceSeeder error: ' . $e->getMessage());
                }
            }
        }
    }
}
