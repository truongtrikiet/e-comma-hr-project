<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\DayEnum;
use App\Enum\DurationType;
use App\Enum\FurloughStatus;
use App\Enum\UseBalanceFurloughEnum;
use App\Helpers\DayEnumHelper;
use App\Models\Furlough;
use App\Models\FurloughBalance;
use App\Models\FurloughPolicy;
use App\Models\SchoolWorkingCalendar;
use App\Models\User;
use App\Notifications\FurloughRequestReviewed;
use App\Repositories\Furlough\FurloughRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FurloughRequestSubmitted;
use Carbon\Carbon;
use RuntimeException;

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
            $data['furlough_balance_id'] = null;

            $policy = $this->getFurloughPolicy(
                $user,
                $data['furlough_type_id']
            );

            if (!$policy) {
                session()->flash(NOTIFICATION_ERROR, __('No furlough policy found for your employee type and selected furlough type. Please contact admin for support.'));
                return false;
            }

            $numberOfDays = $this->calculateNumberOfDays($data);

            if ($numberOfDays <= 0) {
                // session()->flash(NOTIFICATION_ERROR, __('The calculated number of days is zero. Please check start/end dates or duration type.'));
                return false;
            }

            $useBalance = $data['use_balance'] ?? null;

            if ($useBalance) {
                $balance = $this->getFurloughBalance(
                    $user->id,
                    $data['furlough_type_id']
                );

                if (!$balance) {
                    // session()->flash(NOTIFICATION_ERROR, __('No furlough balance found for this furlough type. Please contact admin for support.'));
                    return false;
                }

                $carry = (float) ($balance->carry_remaining_days ?? 0);
                $remaining = (float) ($balance->remaining_days ?? 0);
                $available = round($carry + $remaining, 2);

                if ($numberOfDays > $available) {
                    // session()->flash(NOTIFICATION_ERROR, __('You do not have enough furlough balance. Requested :req, available :avail', ['req' => $numberOfDays, 'avail' => $available]));
                    return false;
                }

                $data['furlough_balance_id'] = $balance->id;
            }

            $data['number_of_days'] = $numberOfDays;

            $durationType = DurationType::tryFrom($data['duration_type'] ?? null);
            if ($durationType === DurationType::HALF_DAY) {
                $data['start_time'] = $data['start_time'] ?? null;
                $data['end_time'] = $data['end_time'] ?? null;
            } else if ($durationType === DurationType::FULL_DAY) {
                $hasOverlap = Furlough::query()
                    ->where('user_id', $user->id)
                    ->where('school_id', $data['school_id'])
                    ->whereIn('furlough_status', [
                        FurloughStatus::APPROVED,
                    ])
                    ->where(function ($q) use ($data) {
                        $q->whereDate('start_time', '<=', $data['end_time'])
                        ->whereDate('end_time', '>=', $data['start_time']);
                    })
                    ->exists();

                if ($hasOverlap) {
                    // session()->flash(
                    //     NOTIFICATION_ERROR,
                    //     __('You already have a furlough request that overlaps with the selected dates.')
                    // );
                    return false;
                }
            }

            $data['use_balance'] = $useBalance;
            $data['furlough_status'] = FurloughStatus::PENDING;

            $furlough = $this->furloughRepository->create($data);

            $hrRoles = User::query()
                ->where('school_id', $furlough->school_id)
                ->whereHas('roles', fn ($q) => $q->where('name', Acl::ROLE_HR))
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($hrRoles as $hr) {
                $hr->notify(
                    new FurloughRequestSubmitted($furlough)
                );
            }

            DB::commit();

            return $furlough;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating furlough', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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
        } catch (\Throwable $e) {
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
        $policy = $this->getFurloughPolicy($furlough->user, $furlough->furlough_type_id);
        if (! $policy) {
            throw new RuntimeException('No valid furlough policy found');
        }

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

            if ((float) ($furlough->number_of_days ?? 0) <= 0) {
                $recalc = $this->calculateNumberOfDays([
                    'start_time' => $furlough->start_time instanceof \DateTimeInterface ? $furlough->start_time->toDateTimeString() : $furlough->start_time,
                    'end_time' => $furlough->end_time instanceof \DateTimeInterface ? $furlough->end_time->toDateTimeString() : $furlough->end_time,
                    'duration_type' => $furlough->duration_type instanceof \BackedEnum ? $furlough->duration_type->value : $furlough->duration_type,
                    'school_id' => $furlough->school_id ?? session('school_id'),
                ]);

                Log::warning('FurloughService::approved recalculated days', [
                    'furlough_id' => $furlough->id,
                    'old_number_of_days' => $furlough->number_of_days,
                    'recalculated' => $recalc,
                ]);

                if ($recalc <= 0) {
                    throw new RuntimeException('Cannot approve furlough with 0 calculated days. Please verify start/end dates or duration.');
                }

                $furlough->number_of_days = $recalc;
                $furlough->save();
            }

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
        ])->first();
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
            $rawStart = Carbon::parse($data['start_time']);
            $rawEnd = Carbon::parse($data['end_time']);

            $start = $rawStart->startOfDay();
            $end = $rawEnd->startOfDay();

            // If UI convention sends an exclusive end at midnight (00:00:00 of next day), treat it as previous day
            if ($rawEnd->format('H:i:s') === '00:00:00' && $end->gt($start)) {
                $end = $end->subDay();
                Log::debug('calculateNumberOfDays: adjusted end (midnight exclusive) by -1 day', [
                    'original_end' => $rawEnd->toDateTimeString(),
                    'adjusted_end' => $end->toDateString(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('calculateNumberOfDays: invalid dates', $data);
            return 0.0;
        }

        if ($start->gt($end)) {
            return 0.0;
        }

        $schoolId = $data['school_id'] ?? session('school_id');
        $workingDays = null;

        if ($schoolId) {
            $calendar = SchoolWorkingCalendar::where('school_id', $schoolId)
                ->where('is_active', ActiveStatus::ACTIVE->value)
                ->latest()
                ->first();

            if ($calendar && is_array($calendar->working_days)) {
                $workingDays = array_map('intval', $calendar->working_days);
                Log::info('calculateNumberOfDays: using school calendar working_days', [
                    'school_id' => $schoolId,
                    'working_days' => $workingDays,
                    'calendar_id' => $calendar->id,
                ]);
            }
        }

        $days = 0;
        $current = $start->copy();

            while ($current->lte($end)) {

                if (is_array($workingDays)) {
                    $dayEnumVal = DayEnumHelper::fromCarbon($current);
                    $isWorking = in_array($dayEnumVal, $workingDays, true);
                } else {
                    $isWorking = $current->isWeekday();
                }

                Log::debug('calculateNumberOfDays: checking day', [
                    'date' => $current->toDateString(),
                    'day_enum' => isset($dayEnumVal) ? $dayEnumVal : null,
                    'is_working' => $isWorking,
                ]);

                if ($isWorking) {
                    $days++;
                }

                $current->addDay();
            }

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

        $days = (float) $furlough->number_of_days;

        $carry = (float) $balance->carry_remaining_days;
        $remaining = (float) $balance->remaining_days;

        Log::info('Deducting furlough balance start', [
            'furlough_id' => $furlough->id,
            'user_id' => $furlough->user_id,
            'furlough_type_id' => $furlough->furlough_type_id,
            'days' => $days,
            'carry_before' => $carry,
            'remaining_before' => $remaining,
            'used_before' => $balance->used_days ?? 0,
        ]);

        if ($days > ($carry + $remaining)) {
            throw new RuntimeException('Not enough furlough balance');
        }

        $fromCarry = min($carry, $days);
        $balance->carry_remaining_days = round($carry - $fromCarry, 2);

        $left = round($days - $fromCarry, 2);

        if ($left > 0) {
            $balance->remaining_days = round($remaining - $left, 2);
        }

        $balance->used_days = round(
            ($balance->used_days ?? 0) + $days,
            2
        );

        $balance->save();

        Log::info('Deducting furlough balance end', [
            'furlough_id' => $furlough->id,
            'user_id' => $furlough->user_id,
            'carry_after' => $balance->carry_remaining_days,
            'remaining_after' => $balance->remaining_days,
            'used_after' => $balance->used_days,
        ]);
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

        if (! $balance) {
            throw new RuntimeException('No furlough balance found');
        }

        $days = (float) $furlough->number_of_days;
        Log::info('Restoring furlough balance start', [
            'furlough_id' => $furlough->id,
            'user_id' => $furlough->user_id,
            'furlough_type_id' => $furlough->furlough_type_id,
            'days' => $days,
            'carry_before' => $balance->carry_remaining_days ?? 0,
            'remaining_before' => $balance->remaining_days ?? 0,
            'used_before' => $balance->used_days ?? 0,
        ]);
        $policy = $this->getFurloughPolicy($furlough->user, $furlough->furlough_type_id);
        $maxDays = (float) ($policy->max_days ?? 0);

        $restoreToRemaining = min(
            $days,
            max(0, $maxDays - $balance->remaining_days)
        );

        $balance->remaining_days = round(
            $balance->remaining_days + $restoreToRemaining,
            2
        );

        $left = round($days - $restoreToRemaining, 2);

        if ($left > 0) {
            $balance->carry_remaining_days = round(
                ($balance->carry_remaining_days ?? 0) + $left,
                2
            );
        }

        $balance->used_days = round(
            max(0, ($balance->used_days ?? 0) - $days),
            2
        );

        $balance->save();

        Log::info('Restoring furlough balance end', [
            'furlough_id' => $furlough->id,
            'user_id' => $furlough->user_id,
            'carry_after' => $balance->carry_remaining_days ?? 0,
            'remaining_after' => $balance->remaining_days ?? 0,
            'used_after' => $balance->used_days ?? 0,
        ]);
    }
}
