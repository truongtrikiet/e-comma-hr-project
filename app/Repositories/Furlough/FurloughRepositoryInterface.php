<?php

namespace App\Repositories\Furlough;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the Furlough Model
 */
interface FurloughRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;

    /**
     * Paginating, ordering and searching through pages for server side index table by self.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFilteringByStaff($searchParams): LengthAwarePaginator;

    /**
     * Approved furlough request.
     */
    public function approved($furlough, $data);
}
