<?php

namespace App\Repositories\School;

use App\Enum\SslStatus;
use App\Models\School;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the School Model
 */
class SchoolRepository extends BaseRepository implements SchoolRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(School $model)
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
    public function serverPaginationFilteringForAdmin($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $sslStatus = Arr::get($searchParams, 'ssl_status', null);

        $query = $this->model->query();

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('sub_domain', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($sslStatus)) {
            $query->where('ssl_status', $sslStatus);
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * Get all active schools.
     */
    public function getSchoolActive()
    {
        return $this->model->where('ssl_status', SslStatus::ACTIVE)->get();
    }
}
