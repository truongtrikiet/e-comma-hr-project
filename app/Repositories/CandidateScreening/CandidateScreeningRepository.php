<?php

namespace App\Repositories\CandidateScreening;

use App\Enum\ActiveStatus;
use App\Models\CandidateScreening;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The repository for the CandidateScreening Model
 */
class CandidateScreeningRepository extends BaseRepository implements CandidateScreeningRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(CandidateScreening $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return array
     */
    public function serverPaginationFiltering(array $params): array
    {
        $start  = intval($params['start'] ?? 0);
        $length = intval($params['length'] ?? self::PER_PAGE);

        $keyword = data_get($params, 'search.value');
        $schoolId = $params['school_id'] ?? null;
        $positionType = $params['position_type'] ?? null;
        $status = $params['status'] ?? null;

        $query = $this->model->with(['school', 'aiProfile']);

        if ($keyword) {
            $query->where('candidate_name', 'LIKE', "%{$keyword}%");
        }

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        if ($positionType) {
            $query->where('position_type', $positionType);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $total = $query->count();

        $data = $query
            ->latest()
            ->skip($start)
            ->take($length)
            ->get();

        return [
            'data' => $data,
            'total' => $total,
            'filtered' => $total,
        ];
    }
}
