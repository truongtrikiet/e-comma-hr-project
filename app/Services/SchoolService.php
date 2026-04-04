<?php

namespace App\Services;

use App\Repositories\SchoolWorkingCalendar\SchoolWorkingCalendarRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enum\ActiveStatus;

class SchoolService
{
    public function __construct(
        protected SchoolWorkingCalendarRepositoryInterface $schoolWorkingCalendarRepository,
    ) {
        //
    }

    /**
     * Override school working calendar create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['is_active'] = ActiveStatus::ACTIVE->value;

            $schoolWorkingCalendar = $this->schoolWorkingCalendarRepository->create($data);

            DB::commit();

            return $schoolWorkingCalendar;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error creating school working calendar: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Override school working calendar update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            $schoolWorkingCalendar = $this->schoolWorkingCalendarRepository->update($model, $data);

            DB::commit();

            return $schoolWorkingCalendar;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error updating school working calendar: ' . $e->getMessage());
            return false;
        }
    }
}
