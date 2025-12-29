<?php

namespace App\Repositories\User;

use App\Enum\UserStatus;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository interface for the User Model
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin(array $searchParams): LengthAwarePaginator|Collection;

    /**
     * Get list user by status.
     *
     * @return Collection
     */
    public function getDataByStatus(): Collection;

    /**
     * Get data for scroll pagination in send email manual.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchDataScrollPagination(): LengthAwarePaginator;

    /**
     * Retrieve table data where the values of a specified field are in the given array
     *
     * @param string $field
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDataInArray(string $field = 'id', array $values = []): Collection;

    /**
     * Counting the number of employees
     *
     * @return mixed
     */
    public function getTotalUser();

    /**
     * Get all users except the one with the specified ID.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allExcept(int $userId);

    /**
     * Get all users with the role of admin or super admin.
     *
     * @return Collection
     */
    public function getAdminsAndSuperAdmins(): Collection;

    /**
     * Get all users with the role staff only.
     *
     * @return Collection
     */
    public function getStaffsByTitle(array $titleIds);

    /**
     * Get experiences for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExperiencesForProfile(User $user): Collection;

    /**
     * Get educations for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEducationsForProfile(User $user): Collection;

    /**
     * Get skills for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSkillsForProfile(User $user): Collection;

    /**
     * Get birthday of staffs.
     *
     * @return Collection
     */
    public function getBirthdayForStaff();

    /**
     * Get habits for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHabitsForProfile(User $user): Collection;

    /**
     * Get anniversary day of users.
     *
     * @return Collection
     */
    public function getAnniversaryDayForStaff();

    /**
     * Get all users with the role super admin only.
     *
     * @return Collection
     */
    
    public function updateNotificationSetting(User $user, bool $enabled): User;

    /**
     * Get all users with the role of staff.
     *
     * @return Collection
     */
    public function getStaffOnly(): Collection;

}
