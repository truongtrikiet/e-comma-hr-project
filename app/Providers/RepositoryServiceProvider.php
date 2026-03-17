<?php

namespace App\Providers;

use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\EmployeeType\EmployeeTypeRepository;
use App\Repositories\EmployeeType\EmployeeTypeRepositoryInterface;
use App\Repositories\Furlough\FurloughRepository;
use App\Repositories\Furlough\FurloughRepositoryInterface;
use App\Repositories\FurloughType\FurloughTypeRepository;
use App\Repositories\FurloughType\FurloughTypeRepositoryInterface;
use App\Repositories\HolidaySchedule\HolidayScheduleRepository;
use App\Repositories\HolidaySchedule\HolidayScheduleRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\School\SchoolRepository;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\Subject\SubjectRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(SchoolRepositoryInterface::class, SchoolRepository::class);
        $this->app->singleton(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->singleton(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->singleton(FurloughTypeRepositoryInterface::class, FurloughTypeRepository::class);
        $this->app->singleton(FurloughRepositoryInterface::class, FurloughRepository::class);
        $this->app->singleton(EmployeeTypeRepositoryInterface::class, EmployeeTypeRepository::class);
        $this->app->singleton(HolidayScheduleRepositoryInterface::class, HolidayScheduleRepository::class);
    }
}
