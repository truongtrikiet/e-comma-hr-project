<?php

namespace App\Services;

use App\Enum\MeetingScheduleStatus;
use App\Repositories\MeetingSchedule\MeetingScheduleRepositoryInterface;
use App\Repositories\MeetingScheduleTarget\MeetingScheduleTargetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Department;
use App\Models\School;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MeetingScheduleCreated;
use App\Enum\MeetingTargetType;;

class MeetingScheduleService
{
    public function __construct(
        protected MeetingScheduleRepositoryInterface $meetingScheduleRepository,
        protected MeetingScheduleTargetRepositoryInterface $meetingScheduleTargetRepository,
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

            $data['created_by'] = auth()->id();
            $data['status'] = MeetingScheduleStatus::UPCOMING->value;

            if (empty($data['school_id'])) {
                $data['school_id'] = auth()->user()->school_id ?? null;
            }

            $meetingSchedule = $this->meetingScheduleRepository->create(
                collect($data)->except('targets')->toArray()
            );

            if (!empty($data['targets']) && is_array($data['targets'])) {
                foreach ($data['targets'] as $target) {

                    $targetType = $target['target_type'] ?? null;
                    $targetIds  = $target['target_ids'] ?? [];

                    if (!$targetType || empty($targetIds)) {
                        continue;
                    }

                    foreach ($targetIds as $targetId) {
                        $this->validateTarget($targetType, $targetId);

                        $meetingSchedule->targets()->create([
                            'target_type' => $targetType,
                            'target_id'   => $targetId,
                        ]);
                    }
                }
            }

            DB::commit();

            // Notify participants after commit
            try {
                $recipients = collect();

                if (!empty($data['targets']) && is_array($data['targets'])) {
                    foreach ($data['targets'] as $target) {
                        $targetType = $target['target_type'] ?? null;
                        $targetIds  = $target['target_ids'] ?? [];

                        if (!$targetType || empty($targetIds)) continue;

                        foreach ($targetIds as $targetId) {
                            switch ((int) $targetType) {
                                case \App\Enum\MeetingTargetType::USER->value:
                                    $u = User::find($targetId);
                                    if ($u) $recipients->push($u);
                                    break;
                                case \App\Enum\MeetingTargetType::DEPARTMENT->value:
                                    $users = User::where('department_id', $targetId)->get();
                                    $recipients = $recipients->merge($users);
                                    break;
                                case \App\Enum\MeetingTargetType::SCHOOL->value:
                                    $users = User::where('school_id', $meetingSchedule->school_id)->get();
                                    $recipients = $recipients->merge($users);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }

                $recipients = $recipients->unique('id')->filter();

                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new MeetingScheduleCreated($meetingSchedule));
                }
            } catch (\Exception $e) {
                Log::error('Error sending meeting created notifications: ' . $e->getMessage());
            }

            return $meetingSchedule;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating furlough: ' . $e->getMessage());
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

            if ($data['status'] != MeetingScheduleStatus::CANCELLED->value) {
                $now = now();
                if ($data['start_time'] > $now) {
                    $data['status'] = MeetingScheduleStatus::UPCOMING->value;
                } elseif ($data['end_time'] < $now) {
                    $data['status'] = MeetingScheduleStatus::COMPLETED->value;
                } else {
                    $data['status'] = MeetingScheduleStatus::ONGOING->value;
                }
            }

            $updatedModel = $this->meetingScheduleRepository->update($model, $data);

            if (!empty($data['targets']) && is_array($data['targets'])) {
                $model->targets()->delete();

                foreach ($data['targets'] as $target) {
                    foreach ($target['target_ids'] as $targetId) {
                        $model->targets()->create([
                            'target_type' => $target['target_type'],
                            'target_id'   => $targetId,
                        ]);
                    }
                }
            }

            DB::commit();

            return $updatedModel;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating furlough: ' . $e->getMessage());
            throw $e;
        }
    }

   /**
    * Validate meeting target before create or update meeting schedule.
    */
    protected function validateTarget(int $targetType, int $targetId): void
    {
        match ($targetType) {
            MeetingTargetType::USER->value =>
                User::whereKey($targetId)->exists()
                    || throw new \RuntimeException('Invalid user target'),

            MeetingTargetType::DEPARTMENT->value =>
                Department::whereKey($targetId)->exists()
                    || throw new \RuntimeException('Invalid department target'),

            MeetingTargetType::SCHOOL->value =>
                School::whereKey($targetId)->exists()
                    || throw new \RuntimeException('Invalid school target'),

            default =>
                throw new \RuntimeException('Invalid meeting target type'),
        };
    }
}
