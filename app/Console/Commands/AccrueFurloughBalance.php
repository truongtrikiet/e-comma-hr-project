<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\FurloughPolicy;
use App\Models\FurloughBalance;
use App\Models\User;
use App\Enum\ActiveStatus;
use App\Enum\ResetTypeEnum;

class AccrueFurloughBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'furlough:accrue {--school_id= : Optional school id to limit processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accrue furlough balances for users according to FurloughPolicy (monthly accrual and resets)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Start accruing furlough balances');

        $now = Carbon::now();
        $policies = FurloughPolicy::query()
            ->when($this->option('school_id'), fn ($q, $id) => $q->where('school_id', $id))
            ->where('status', ActiveStatus::ACTIVE)
            ->get();

        foreach ($policies as $policy) {
            $users = User::where('school_id', $policy->school_id)
                ->whereHas('userProfile', fn ($q) =>
                    $q->where('employee_type_id', $policy->employee_type_id)
                )->pluck('id');

            foreach ($users as $userId) {
                try {
                    DB::transaction(fn () =>
                        $this->processUserPolicy($userId, $policy, $now)
                    );
                } catch (\Throwable $e) {
                    Log::error('Accrual failed', [
                        'user_id' => $userId,
                        'policy_id' => $policy->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info('Finished accruing furlough balances');
        return Command::SUCCESS;
    }

    /**
     * Process accrual/reset for a single user's policy.
     */
    protected function processUserPolicy(int $userId, FurloughPolicy $policy, Carbon $now): void
    {
        $balance = FurloughBalance::lockForUpdate()->firstOrCreate(
            [
                'user_id' => $userId,
                'furlough_type_id' => $policy->furlough_type_id,
            ],
            [
                'total_days' => 0,
                'used_days' => 0,
                'remaining_days' => 0,
            ]
        );

        $this->handleYearlyReset($balance, $policy, $now);
        $this->handleMonthlyAccrual($balance, $policy, $now);
    }

    /**
     * Handle yearly reset logic for a user's furlough balance based on the policy.
     */
    protected function handleYearlyReset(FurloughBalance $balance, FurloughPolicy $policy, Carbon $now): void
    {
        if ($policy->reset_type !== ResetTypeEnum::YEARLY) {
            return;
        }

        if (($policy->reset_month?->value ?? null) !== $now->month) {
            return;
        }

        if ($balance->last_reset_at?->year === $now->year) {
            return;
        }

        if ($policy->carry_forward) {
            $remaining = max(0, (float) $balance->remaining_days);
            $cap = $policy->max_days ? (float) $policy->max_days : null;

            if ($cap !== null) {
                $remaining = min($remaining, $cap);
                $balance->total_days = $cap;
            } else {
                $balance->total_days = $remaining;
            }

            $balance->remaining_days = $remaining;
            $balance->used_days = 0;
        } else {
            $cap = (float) ($policy->max_days ?? 0);
            $balance->total_days = $cap;
            $balance->used_days = 0;
            $balance->remaining_days = $cap;
        }

        $balance->last_reset_at = $now;
        $balance->save();
    }

    /**
     * Handle monthly accrual logic for a user's furlough balance based on the policy.
     */
    protected function handleMonthlyAccrual(FurloughBalance $balance, FurloughPolicy $policy, Carbon $now): void
    {
        $accrual = (float) ($policy->accrual_per_month ?? 0);
        if ($accrual <= 0) return;

        $monthStart = $now->copy()->startOfMonth();
        if ($balance->last_accrual_at && $balance->last_accrual_at >= $monthStart) {
            return;
        }

        $cap = $policy->max_days ? (float) $policy->max_days : null;

        $balance->total_days = $cap
            ? min($cap, $balance->total_days + $accrual)
            : $balance->total_days + $accrual;

        $balance->remaining_days = $cap
            ? min($cap - $balance->used_days, $balance->remaining_days + $accrual)
            : $balance->remaining_days + $accrual;

        $balance->last_accrual_at = $now;
        $balance->save();
    }
}
