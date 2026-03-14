<?php

namespace App\Repositories\EmployeeType;

use App\Enum\ActiveStatus;
use App\Repositories\BaseRepository;
use App\Models\EmployeeType;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the EmployeeType Model
 */
class EmployeeTypeRepository extends BaseRepository implements EmployeeTypeRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(EmployeeType $model)
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

    /**
     * Get employee type active.
     */
    public function getActiveEmployeeTypes()
    {
        return $this->model->where('status', ActiveStatus::ACTIVE)->get();
    }
}