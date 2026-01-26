<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Models\School;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the Department Model
 */
class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Department $model)
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
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query();

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }
}
