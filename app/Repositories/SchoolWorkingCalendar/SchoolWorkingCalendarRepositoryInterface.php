<?php

namespace App\Repositories\SchoolWorkingCalendar;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\SchoolWorkingCalendar;

/**
 * The repository interface for the SchoolWorkingCalendar Model
 */
interface SchoolWorkingCalendarRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;

    /**
     * Get working calendar by school.
     */
    public function getWorkingCalendarBySchool($schoolId): ?SchoolWorkingCalendar;
}
