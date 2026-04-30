<?php

namespace App\Repositories\Salary;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the Salary Model
 */
interface SalaryRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin(array $searchParams): LengthAwarePaginator;

    /**
     * Paginating, ordering and searching through pages for server side index table by self.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFilteringByStaff($searchParams): LengthAwarePaginator;

    /**
     * Summary of getCurrentSalary
     * @param mixed $userId
     * @return void
     */
    public function getCurrentSalary($userId);

}
