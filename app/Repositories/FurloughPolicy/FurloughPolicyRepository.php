<?php

namespace App\Repositories\FurloughPolicy;

use App\Enum\ActiveStatus;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\FurloughPolicy;

/**
 * The repository for the FurloughPolicy Model
 */
class FurloughPolicyRepository extends BaseRepository implements FurloughPolicyRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(FurloughPolicy $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $schoolId = Arr::get($searchParams, 'school_id', null);
        $employeeTypeId = Arr::get($searchParams, 'employee_type_id', null);
        $furloughTypeId = Arr::get($searchParams, 'furlough_type_id', null);
        $isPaid = Arr::get($searchParams, 'is_paid', null);
        $resetType = Arr::get($searchParams, 'reset_type', null);
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with(['school', 'employeeType', 'furloughType']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            // $query->where(function ($q) use ($keyword) {
            //     $q->where('name', 'LIKE', '%' . $keyword . '%');
            // });
        }

        if (!is_null($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        if (!is_null($employeeTypeId)) {
            $query->where('employee_type_id', $employeeTypeId);
        }

        if (!is_null($furloughTypeId)) {
            $query->where('furlough_type_id', $furloughTypeId);
        }

        if (!is_null($isPaid)) {
            $query->where('is_paid', $isPaid);
        }

        if (!is_null($resetType)) {
            $query->where('reset_type', $resetType);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }
}
