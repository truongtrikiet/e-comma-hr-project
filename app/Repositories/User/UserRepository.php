<?php

namespace App\Repositories\User;

use App\Acl\Acl;
use App\Enum\PinStatus;
use App\Enum\PostStatus;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Post;
use App\Enum\UserStatus;
use App\Enum\SalaryStatus;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository for the User Model
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin($searchParams): LengthAwarePaginator|Collection
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $roles = Arr::get($searchParams, 'role_id', null);
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with(['roles']);

        if ($roles) {
            $rolesArray = explode(',', $roles);
            $query->whereHas('roles', function ($q) use ($rolesArray) {
                $q->whereIn('id', $rolesArray);
            });
        }

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('id', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * Get list user by status.
     *
     * @param $status
     * @return Collection
     */
    public function getDataByStatus(UserStatus $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * @inheritdoc
     */
    public function fetchDataScrollPagination(): LengthAwarePaginator
    {
        return $this->model->active()->paginate(self::PER_PAGE);
    }

    /**
     * Retrieve table data where the values of a specified field are in the given array
     *
     * @param string $field
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDataInArray(string $field = 'id', array $values = []): Collection
    {
        return $this->model->whereIn($field, $values)->get();
    }

    /**
     * Counting the number of employees
     *
     * @return mixed
     */
    public function getTotalUser()
    {
        return $this->model->count();
    }

    /**
     * @inheritdoc
     */
    public function destroy($model)
    {
        try {
            DB::beginTransaction();

            $model->furloughs()->delete();

            $model->salary()->delete();

            $model->departments()->detach();

            $model->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get all users except the one with the specified ID.
     *
     * @param int $userId
     * @return Collection
     */
    public function allExcept(int $userId): Collection
    {
        return $this->model->where('id', '!=', $userId)->get();
    }

    /**
     * Get all users with the role of admin or super admin.
     *
     * @return Collection
     */
    public function getAdminsAndSuperAdmins(): Collection
    {
        return $this->model->where(function ($query) {
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', [Acl::ROLE_ADMIN, Acl::ROLE_SUPER_ADMIN]);
            });
        })->get();
    }

    /**
     * Get experiences for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExperiencesForProfile(User $user): Collection
    {
        return $user->experiences()->orderByDesc('start_date')->orderByDesc('end_date')->get();
    }

    /**
     * Get educations for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEducationsForProfile(User $user): Collection
    {
        return $user->educations()->orderByDesc('start_date')->orderByDesc('end_date')->get();
    }

    /**
     * Get skills for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSkillsForProfile(User $user): Collection
    {
        return $user->skills()->latest()->get();
    }

    /**
     * Get birthday of staffs.
     *
     * @return Collection
     */
    public function getBirthdayForStaff()
    {
        $today = Carbon::now();

        return $this->model->whereHas('userProfile', function ($query) use ($today) {
            $query->whereNotNull('birth')
                ->whereMonth('birth', $today->month)
                ->whereDay('birth', $today->day);
        })
            ->with('userProfile')->get() ?? collect();
    }

    /**
     * Get habits for profile.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHabitsForProfile(User $user): Collection
    {
        return $user->habits()->latest()->get();
    }
    /**
     * Get anniversary day of staffs.
     *
     * @return Collection
     */
    public function getAnniversaryDayForStaff()
    {
        $today = Carbon::now();

        return $this->model->whereHas('userProfile', function ($query) use ($today) {
            $query->whereNotNull('company_entry_date')
                ->whereMonth('company_entry_date', $today->month)
                ->whereDay('company_entry_date', $today->day);
        })
            ->with('userProfile')->get() ?? collect();
    }

    /**
     * Get all users with the role staff only.
     *
     * @return Collection
     */
    public function getStaffsByTitle(array $titleIds)
    {
        return $this->model
            ->whereHas('titles', function ($q) use ($titleIds) {
                $q->whereIn('titles.id', $titleIds);
            })
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function updateNotificationSetting(User $user, bool $enabled): User
    {
        $user->notifications_enabled = $enabled;
        $user->save();

        return $user;
    }

    /**
     * Get all users with the role of staff.
     *
     * @return Collection
     */
    public function getStaffOnly(): Collection
    {
        return $this->model->where(function ($query) {
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', [Acl::ROLE_STAFF]);
            });
        })->get();
    }
}
