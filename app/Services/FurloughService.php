<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\DurationType;
use App\Enum\FurloughStatus;
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

            $newStatus = $data['furlough_status'];
            $oldStatus = $furlough->furlough_status;

            if ($newStatus === FurloughStatus::APPROVED->value && $oldStatus !== FurloughStatus::APPROVED->value && $furlough->use_balance) {
                $balance = FurloughBalance::where([
                    'user_id' => $furlough->user_id,
                    'furlough_type_id' => $furlough->furlough_type_id,
                ])->lockForUpdate()->first();

                if (!$balance) {
                    session()->flash(NOTIFICATION_ERROR, __('No furlough balance found to approve this request.'));
                    return false;
                }

                $number = (float) $furlough->number_of_days;
                $newUsed = round(($balance->used_days ?? 0) + $number, 2);

                if ($newUsed > ($balance->total_days ?? 0)) {
                    session()->flash(NOTIFICATION_ERROR, __('Not enough furlough balance to approve this request. Please contact admin for support.'));
                    return false;
                }

                $balance->used_days = $newUsed;
                $balance->remaining_days = round(max(0, ($balance->total_days ?? 0) - $newUsed), 2);
                $balance->save();
            }

            if ($newStatus !== FurloughStatus::APPROVED->value && $oldStatus === FurloughStatus::APPROVED->value && $furlough->use_balance) {
                $balance = FurloughBalance::where([
                    'user_id' => $furlough->user_id,
                    'furlough_type_id' => $furlough->furlough_type_id,
                ])->lockForUpdate()->first();

                if ($balance) {
                    $number = (float) $furlough->number_of_days;
                    $newUsed = round(max(0, ($balance->used_days ?? 0) - $number), 2);
                    $balance->used_days = $newUsed;
                    $balance->remaining_days = round(min($balance->total_days ?? 0, ($balance->total_days ?? 0) - $newUsed), 2);
                    $balance->save();
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

    protected function getFurloughBalance($userId, $furloughTypeId)
    {
        return FurloughBalance::where([
            'user_id' => $userId,
            'furlough_type_id' => $furloughTypeId,
        ])->firstOrFail();
    }

    protected function calculateNumberOfDays(array $data): float
    {
        if ($data['duration_type'] === DurationType::HALF_DAY) {
            return 0.5;
        }

        return Carbon::parse($data['start_time'])
            ->diffInDaysFiltered(fn ($date) => $date->isWeekday(),
                Carbon::parse($data['end_time'])->addDay()
            );
    }
}
