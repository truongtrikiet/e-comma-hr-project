<?php

namespace App\Repositories\SalaryPropose;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the SalaryPropose Model
 */
interface SalaryProposeRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;
}
