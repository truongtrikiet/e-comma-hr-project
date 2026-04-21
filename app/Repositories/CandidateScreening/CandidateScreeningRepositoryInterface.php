<?php

namespace App\Repositories\CandidateScreening;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the CandidateScreening Model
 */
interface CandidateScreeningRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return array
     */
    public function serverPaginationFiltering(array $params): array;
}
