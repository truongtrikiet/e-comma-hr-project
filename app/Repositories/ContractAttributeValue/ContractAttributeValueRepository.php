<?php

namespace App\Repositories\ContractAttributeValue;

use App\Repositories\BaseRepository;
use App\Models\ContractAttributeValue;

/**
 * The repository for UserWallet Model
 */
class ContractAttributeValueRepository extends BaseRepository implements ContractAttributeValueRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(ContractAttributeValue $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
