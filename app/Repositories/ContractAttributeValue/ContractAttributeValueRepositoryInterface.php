<?php

namespace App\Repositories\ContractAttributeValue;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the User Model
 */
interface ContractAttributeValueRepositoryInterface extends RepositoryInterface
{
    /**
     * Get attribute values by contract ID
      *
      * @param int $contractId
      * @return array
     */
    public function getValuesByContractId(int $contractId): array;
}
