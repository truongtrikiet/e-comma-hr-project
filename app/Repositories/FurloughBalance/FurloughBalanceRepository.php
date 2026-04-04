<?php

namespace App\Repositories\FurloughBalance;

use App\Models\FurloughBalance;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the FurloughBalance Model
 */
class FurloughBalanceRepository extends BaseRepository implements FurloughBalanceRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(FurloughBalance $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
