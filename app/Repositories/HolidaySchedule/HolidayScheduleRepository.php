<?php

namespace App\Repositories\HolidaySchedule;

use App\Enum\ActiveStatus;
use App\Models\HolidaySchedule;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * The repository for the HolidaySchedule Model
 */
class HolidayScheduleRepository extends BaseRepository implements HolidayScheduleRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(HolidaySchedule $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['created_by'] = auth()->id();
            $data['total_days'] = $this->calculateTotalDays($data['start_date'], $data['end_date']);
            $data['status'] = ActiveStatus::ACTIVE->value;

            $holidaySchedule = $this->model->create($data);

            DB::commit();

            return $holidaySchedule;
        } catch (\Exception $e) {
            Log::info('Error creating holiday schedule: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Override update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            $data['updated_by'] = auth()->id();
            $data['status'] = $data['status'] ?? $model->status;

            if (isset($data['start_date']) && isset($data['end_date'])) {
                $data['total_days'] = $this->calculateTotalDays($data['start_date'], $data['end_date']);
            }

            $model->update($data);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            Log::info('Error updating holiday schedule: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate total days of holiday schedule based on start_date and end_date
     */
    private function calculateTotalDays($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        return $start->diffInDays($end) + 1;
    }
}