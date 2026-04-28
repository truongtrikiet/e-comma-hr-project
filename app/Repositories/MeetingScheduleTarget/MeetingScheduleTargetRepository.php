<?php

namespace App\Repositories\MeetingScheduleTarget;

use App\Models\MeetingScheduleTarget;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the MeetingScheduleTarget Model
 */
class MeetingScheduleTargetRepository extends BaseRepository implements MeetingScheduleTargetRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(MeetingScheduleTarget $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
