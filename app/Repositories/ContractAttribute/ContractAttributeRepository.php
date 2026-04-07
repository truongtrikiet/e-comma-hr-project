<?php

namespace App\Repositories\ContractAttribute;

use App\Repositories\BaseRepository;
use App\Models\ContractAttribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

/**
 * The repository for ContractAttribute Model
 */
class ContractAttributeRepository extends BaseRepository implements ContractAttributeRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * @inheritdoc
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function __construct(ContractAttribute $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * {@inheritdoc}
     */
    public function serverPaginationFilteringForAdmin($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');

        $query = $this->model->query()->withCount('contractTypes');

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->whereAny(['name', 'key', 'id'], 'LIKE', '%' . $keyword . '%');
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataInArray(string $field = 'id', array $values = []): Collection
    {
        return $this->model->whereIn($field, $values)->get();
    }
}
