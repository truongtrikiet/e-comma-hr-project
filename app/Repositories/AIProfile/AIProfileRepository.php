<?php

namespace App\Repositories\AIProfile;

use App\Enum\ActiveStatus;
use App\Models\AIProfile;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * The repository for the AIProfile Model
 */
class AIProfileRepository extends BaseRepository implements AIProfileRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(AIProfile $model)
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
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with(['school']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * Get profile by school.
     */
    public function getAIProfileBySchool($schoolId)
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->where('status', ActiveStatus::ACTIVE->value)
            ->get();
    }
}
