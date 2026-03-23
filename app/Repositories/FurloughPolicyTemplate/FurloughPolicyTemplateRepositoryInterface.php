<?php

namespace App\Repositories\FurloughPolicyTemplate;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the FurloughPolicyTemplate Model
 */
interface FurloughPolicyTemplateRepositoryInterface extends RepositoryInterface
{
     /**
     * Paginating, ordering and searching through pages for server side index table.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;

    /**
    * Get furlough policy templates active status.
    */
   public function getFurloughPolicyTemplate();
}