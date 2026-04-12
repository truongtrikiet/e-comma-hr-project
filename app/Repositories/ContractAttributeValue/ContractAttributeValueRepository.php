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

    /**
     * Get attribute values by contract ID
      *
      * @param int $contractId
      * @return array
     */
    public function getValuesByContractId(int $contractId): array
    {
        return $this->model->where('contract_id', $contractId)
            ->pluck('value', 'contract_type_attribute_id')
            ->toArray();
    }
}
