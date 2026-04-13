<?php

namespace App\Repositories\Contract;

use App\Models\Contract;
use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository interface for the Contract Model
 */
interface ContractRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin(array $searchParams): LengthAwarePaginator;

    /**
     * Paginating, ordering and searching through pages for server side index table for the Staff.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForStaff(array $searchParams): LengthAwarePaginator;

    /**
     * Paginationg contracts for model.
     *
     * @param $model
     * @return LengthAwarePaginator
     */
    public function serverPaginationContractsForModel($model): LengthAwarePaginator;

    /**
     * Get appendix contract in array for contract.
     * @param \App\Models\Contract $model
     * @param array $values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppendixContractInArrayForContract(Contract $model, array $values): Collection;

    /**
     * Retrieve the list of contracts associated with a specific user.
     *
     * @param \App\Models\User $user The user whose contracts should be retrieved.
     * @return \Illuminate\Database\Eloquent\Collection The collection of contracts belonging to the user.
     */
    public function getContractsByUser($user);

    /**
     * Retrieve a contract by ID, ensuring it belongs to the specified user.
     *
     * @param int $contractId The ID of the contract.
     * @param int $userId The ID of the user.
     * @return Contract|null The contract if found, otherwise null.
     */
    public function getContractByIdAndUser(int $contractId, int $userId);
}
