<?php

namespace App\Repositories\SchoolWorkingCalendar;

use App\Enum\ActiveStatus;
use App\Enum\SslStatus;
use App\Models\SchoolWorkingCalendar;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * The repository for the SchoolWorkingCalendar Model
 */
class SchoolWorkingCalendarRepository extends BaseRepository implements SchoolWorkingCalendarRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(SchoolWorkingCalendar $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $schoolId = Arr::get($searchParams, 'school_id', null);
        $status = Arr::get($searchParams, 'is_active', null);

        $query = $this->model->query();

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
        }

        if (!is_null($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        if (!is_null($status)) {
            $query->where('is_active', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * Get working calendar by school.
     */
    public function getWorkingCalendarBySchool($schoolId): ?SchoolWorkingCalendar
    {
        return $this->model->where('school_id', $schoolId)->first();
    }
}
