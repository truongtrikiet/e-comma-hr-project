<?php

namespace App\Repositories\EmployeeType;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the EmployeeType Model
 */
interface EmployeeTypeRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;

    /**
     * Get employee type active.
     */
    public function getActiveEmployeeTypes();
}