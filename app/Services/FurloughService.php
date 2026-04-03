<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\DurationType;
use App\Enum\FurloughStatus;
use App\Enum\UseBalanceFurloughEnum;
use App\Models\FurloughBalance;
use App\Models\FurloughPolicy;
use App\Models\User;
use App\Notifications\FurloughRequestReviewed;
use App\Repositories\Furlough\FurloughRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FurloughRequestSubmitted;
use Carbon\Carbon;

class FurloughService
{
    public function __construct(
        protected FurloughRepositoryInterface $furloughRepository,
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

            $user = auth()->user();

            $data['user_id']   = $user->id;
            $data['school_id'] = $user->school_id ?? session('school_id');

            $policy = $this->getFurloughPolicy(
                $user,
                $data['furlough_type_id']
            );

            if (!$policy) {
                session()->flash(NOTIFICATION_ERROR, __('No furlough policy found for your employee type and selected furlough type. Please contact admin for support.'));
                return false;
            }

            $numberOfDays = $this->calculateNumberOfDays($data);

            $useBalance = $data['use_balance'] ?? null;

            if ($useBalance) {
                $balance = $this->getFurloughBalance(
                    $user->id,
                    $data['furlough_type_id']
                );

                if (!$balance) {
                    session()->flash(NOTIFICATION_ERROR, __('No furlough balance found for this furlough type. Please contact admin for support.'));
                    return false;
                }

                if ($balance->remaining_days < $numberOfDays) {
                    session()->flash(NOTIFICATION_ERROR, __('You do not have enough furlough balance. Please adjust the number of days or choose to not use balance.'));
                    return false;
                }
            }

            $data['number_of_days'] = $numberOfDays;
            $data['use_balance'] = $useBalance;
            $data['furlough_status'] = FurloughStatus::PENDING;

            $furlough = $this->furloughRepository->create($data);

            $admins = User::query()
                ->where('school_id', $furlough->school_id)
                ->whereHas('roles', fn ($q) => $q->where('name', Acl::ROLE_ADMIN))
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(
                    new FurloughRequestSubmitted($furlough)
                );
            }

            DB::commit();

            return $furlough;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating furlough: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Override update method.
     */
    public function update($furlough, $data)
    {
        try {
            DB::beginTransaction();

            if ($furlough->furlough_status !== FurloughStatus::PENDING) {
                return $furlough;
            }

            $user = auth()->user();

            $data['user_id']   = $user->id;
            $data['school_id'] = $user->school_id ?? session('school_id');

            unset(
                $data['number_of_days'],
                $data['use_balance'],
                $data['furlough_status']
            );

            $this->furloughRepository->update($furlough, $data);

            DB::commit();

            return $furlough;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating furlough: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approved furlough request.
     */
    public function approved($furlough, $data)
    {
        try {
            DB::beginTransaction();

            $newStatus = (int) $data['furlough_status'];
            $oldStatus = (int) $furlough->furlough_status->value;

            Log::info('FurloughService::approved start', [
                'furlough_id' => $furlough->id,
                'user_id' => $furlough->user_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'use_balance' => $furlough->use_balance,
                'number_of_days' => $furlough->number_of_days,
            ]);

            $useBalance = $furlough->use_balance instanceof UseBalanceFurloughEnum
                ? ($furlough->use_balance === UseBalanceFurloughEnum::USE)
                : (UseBalanceFurloughEnum::tryFrom($furlough->use_balance) === UseBalanceFurloughEnum::USE);

            if ($useBalance) {
                switch (true) {
                    case (
                        $oldStatus !== FurloughStatus::APPROVED->value &&
                        $newStatus === FurloughStatus::APPROVED->value
                    ):
                        $this->deductFurloughBalance($furlough);
                        break;

                    case (
                        $oldStatus === FurloughStatus::APPROVED->value &&
                        $newStatus !== FurloughStatus::APPROVED->value
                    ):
                        $this->restoreFurloughBalance($furlough);
                        break;
                }
            }

            $furlough->update([
                'furlough_status' => $newStatus,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $furlough->user->notify(
                new FurloughRequestReviewed($furlough)
            );

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error approving furlough', [
                'furlough_id' => $furlough->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get furlough policy for user and furlough type.
     */
    protected function getFurloughPolicy($user, $furloughTypeId)
    {
        $employeeTypeId = $user->userProfile->employee_type_id ?? null;

        if (is_null($employeeTypeId)) {
            return null;
        }

        return FurloughPolicy::where('school_id', $user->school_id)
            ->where('employee_type_id', $employeeTypeId)
            ->where('furlough_type_id', $furloughTypeId)
            ->first();
    }

    /**
     * Get furlough balance for user and furlough type.
     */
    protected function getFurloughBalance($userId, $furloughTypeId)
    {
        return FurloughBalance::where([
            'user_id' => $userId,
            'furlough_type_id' => $furloughTypeId,
        ])->firstOrFail();
    }

    /**
     * Calculate the number of days for a furlough request.
     */
    protected function calculateNumberOfDays(array $data): float
    {
        $durationType = DurationType::tryFrom($data['duration_type'] ?? null);

        if ($durationType === DurationType::HALF_DAY) {
            return 0.5;
        }

        try {
            $start = Carbon::parse($data['start_time'])->startOfDay();
            $end = Carbon::parse($data['end_time'])->startOfDay();
        } catch (\Throwable $e) {
            Log::warning('calculateNumberOfDays: invalid dates', ['start' => $data['start_time'], 'end' => $data['end_time']]);
            return 0.0;
        }

        if ($start->gt($end)) {
            return 0.0;
        }

        if ($start->isSameDay($end)) {
            $result = $start->isWeekday() ? 1.0 : 0.0;
            Log::info('calculateNumberOfDays - same day result', ['result' => $result, 'is_weekday' => $start->isWeekday()]);
            return $result;
        }

        $days = 0;
        $current = $start->copy();

        while ($current->lt($end)) {
            if ($current->isWeekday()) {
                $days++;
            }
            $current->addDay();
        }

        Log::info('calculateNumberOfDays - computed days (exclusive end)', ['days' => $days]);
        return (float) $days;
    }

    /**
     * Deduct furlough balance when a furlough request is approved.
     */
    protected function deductFurloughBalance($furlough): void
    {
        $balance = FurloughBalance::where([
            'user_id' => $furlough->user_id,
            'furlough_type_id' => $furlough->furlough_type_id,
        ])->lockForUpdate()->firstOrFail();

        $number = (float) $furlough->number_of_days;

        if ($number > ($balance->remaining_days ?? 0)) {
            session()->flash(NOTIFICATION_ERROR, __('Not enough furlough balance to approve this request. Please contact admin for support.'));
            return;
        }

        $balance->used_days = round(($balance->used_days ?? 0) + $number, 2);
        $balance->remaining_days = round(($balance->remaining_days ?? 0) - $number, 2);
        $balance->save();
    }

    /**
     * Restore furlough balance when a furlough request is rejected or withdrawn.
     */
    protected function restoreFurloughBalance($furlough): void
    {
        $balance = FurloughBalance::where([
            'user_id' => $furlough->user_id,
            'furlough_type_id' => $furlough->furlough_type_id,
        ])->lockForUpdate()->first();

        if (!$balance) {
            session()->flash(NOTIFICATION_ERROR, __('No furlough balance found to restore. Please contact admin for support.'));
            return;
        }

        $number = (float) $furlough->number_of_days;

        $balance->used_days = round(max(0, $balance->used_days - $number), 2);
        $balance->remaining_days = round(($balance->remaining_days ?? 0) + $number, 2);
        $balance->save();
    }
}
