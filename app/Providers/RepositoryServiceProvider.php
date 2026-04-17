<?php

namespace App\Providers;

use App\Repositories\AIProfile\AIProfileRepository;
use App\Repositories\AIProfile\AIProfileRepositoryInterface;
use App\Repositories\AppendixContract\AppendixContractRepository;
use App\Repositories\AppendixContract\AppendixContractRepositoryInterface;
use App\Repositories\Contract\ContractRepository;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\ContractAttribute\ContractAttributeRepository;
use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use App\Repositories\ContractAttributeValue\ContractAttributeValueRepository;
use App\Repositories\ContractAttributeValue\ContractAttributeValueRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepository;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\EmployeeType\EmployeeTypeRepository;
use App\Repositories\EmployeeType\EmployeeTypeRepositoryInterface;
use App\Repositories\Furlough\FurloughRepository;
use App\Repositories\Furlough\FurloughRepositoryInterface;
use App\Repositories\FurloughBalance\FurloughBalanceRepository;
use App\Repositories\FurloughBalance\FurloughBalanceRepositoryInterface;
use App\Repositories\FurloughPolicy\FurloughPolicyRepository;
use App\Repositories\FurloughPolicy\FurloughPolicyRepositoryInterface;
use App\Repositories\FurloughPolicyTemplate\FurloughPolicyTemplateRepository;
use App\Repositories\FurloughPolicyTemplate\FurloughPolicyTemplateRepositoryInterface;
use App\Repositories\FurloughType\FurloughTypeRepository;
use App\Repositories\FurloughType\FurloughTypeRepositoryInterface;
use App\Repositories\HolidaySchedule\HolidayScheduleRepository;
use App\Repositories\HolidaySchedule\HolidayScheduleRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\Salary\SalaryRepositoryInterface;
use App\Repositories\School\SchoolRepository;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Repositories\SchoolWorkingCalendar\SchoolWorkingCalendarRepository;
use App\Repositories\SchoolWorkingCalendar\SchoolWorkingCalendarRepositoryInterface;
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
        $this->app->singleton(FurloughPolicyTemplateRepositoryInterface::class, FurloughPolicyTemplateRepository::class);
        $this->app->singleton(FurloughPolicyRepositoryInterface::class, FurloughPolicyRepository::class);
        $this->app->singleton(FurloughBalanceRepositoryInterface::class, FurloughBalanceRepository::class);
        $this->app->singleton(SchoolWorkingCalendarRepositoryInterface::class, SchoolWorkingCalendarRepository::class);
        $this->app->singleton(ContractRepositoryInterface::class, ContractRepository::class);
        $this->app->singleton(ContractTypeRepositoryInterface::class, ContractTypeRepository::class);
        $this->app->singleton(ContractAttributeRepositoryInterface::class, ContractAttributeRepository::class);
        $this->app->singleton(ContractAttributeValueRepositoryInterface::class, ContractAttributeValueRepository::class);
        $this->app->singleton(AppendixContractRepositoryInterface::class, AppendixContractRepository::class);
        $this->app->singleton(SalaryRepositoryInterface::class, SalaryRepository::class);
        $this->app->singleton(AIProfileRepositoryInterface::class, AIProfileRepository::class);
    }
}
