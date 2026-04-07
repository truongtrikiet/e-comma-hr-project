<?php

namespace App\Repositories\ContractType;

use App\Repositories\BaseRepository;
use App\Models\ContractType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The repository for ContractType Model
 */
class ContractTypeRepository extends BaseRepository implements ContractTypeRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * @inheritdoc
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function __construct(ContractType $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');

        $query = $this->model->query()->with('contractAttributes')->withCount('contracts');

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $keyword = removeVietnameseAccents($keyword);

            $query->where(function ($q) use ($keyword) {
                $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) LIKE ?", ['%' . strtolower($keyword) . '%'])
                    ->orWhereRaw("LOWER(CONVERT(content USING utf8mb4)) LIKE ?", ['%' . strtolower($keyword) . '%'])
                    ->orWhereRaw("LOWER(CONVERT(id USING utf8mb4)) LIKE ?", ['%' . strtolower($keyword) . '%'])
                    ->orWhereHas('contractAttributes', function ($q) use ($keyword) {
                        $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) LIKE ?", ['%' . strtolower($keyword) . '%'])
                            ->orWhereRaw("LOWER(CONVERT(`key` USING utf8mb4)) LIKE ?", ['%' . strtolower($keyword) . '%']);
                    });
            });
        }

        $query->latest();

        return $query->paginate($limit);
    }
}
