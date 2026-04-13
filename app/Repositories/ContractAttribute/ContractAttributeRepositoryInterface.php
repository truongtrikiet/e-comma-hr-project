<?php

namespace App\Repositories\ContractAttribute;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository interface for the ContractAttribute Model
 */
interface ContractAttributeRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin(array $searchParams): LengthAwarePaginator;

    /**
     * Retrieve table data where the values of a specified field are in the given array
     *
     * @param string $field
     * @param array $values
     * @return \Illuminate\Support\Collection
     */
    public function getDataInArray(string $field = 'id', array $values = []): Collection;
}