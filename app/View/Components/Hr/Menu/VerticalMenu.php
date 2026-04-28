<?php

namespace App\View\Components\Hr\Menu;

use App\Acl\Acl;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class VerticalMenu extends Component
{
    public $menuItems;
    public $isMainAgency;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->generateMenu();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hr.menu.vertical-menu');
    }

    private function generateMenu(): void
    {
        $this->isMainAgency = session('school_name') === config('subdomain.agency_main');
        $this->setProperties();
        $this->buildMenuDashboard();
        $this->buildMainMenu();
        $this->buildMenuPurpose();
        $this->buildSystemSetting();
    }

    private function buildMenuDashboard(): void
    {
        $this->menuItems = array_merge($this->menuItems, [
            [
                'type' => 'label',
                'title' => __('general.menu.system_management'),
            ],
            [
                'title' => __('general.menu.system_management'),
                'icon' => 'icon icon-home',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('Dashboard'),
                        'url' => route('hr.dashboard'),
                        'active' => Route::is(['hr.dashboard']),
                        'show' => checkPermission(Acl::PERMISSION_VIEW_MENU_HR),
                    ],
                    [
                        'title' => __('general.menu.holiday_schedule_management.title'),
                        'url' => route('hr.holiday-schedule.index'),
                        'active' => Route::is(['hr.holiday-schedule.*']),
                        'show' => checkPermission(Acl::PERMISSION_HOLIDAY_SCHEDULE_LIST),
                    ],
                ],
            ],
        ]);
    }

    private function buildMainMenu(): void
    {
        $this->menuItems = array_merge($this->menuItems, [
            [
                'type' => 'label',
                'title' => __('general.menu.hr_management'),
            ],
            [
                'title' => __('general.menu.user_management.title'),
                'icon' => 'icon icon-single-04-2',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('general.menu.user_management.user'),
                        'url' => route('hr.user.index'),
                        'active' => Route::is(['hr.user.*']),
                        'show' => checkPermissions([Acl::PERMISSION_USER_LIST]),
                    ],
                    [
                        'title' => __('general.menu.salary_management.manage_salary'),
                        'url' => route('hr.salary.index'),
                        'active' => Route::is(['hr.salary.*']),
                        'show' => checkPermissions([Acl::PERMISSION_SALARY_LIST]),
                    ],
                ],
            ],
            [
                'title' => __('general.menu.candidate_screening_management.title'),
                'icon' => 'icon icon-users-mm',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('general.menu.candidate_screening_management.manage_candidate_screening'),
                        'url' => route('hr.candidate-screening.index'),
                        'active' => Route::is(['hr.candidate-screening.*']),
                        'show' => checkPermissions([Acl::PERMISSION_CANDIDATE_SCREENING_LIST]),
                    ],
                ],
            ],
        ]);
    }

    private function buildSystemSetting(): void
    {
        $this->menuItems = array_merge($this->menuItems, [
            [
                'type' => 'label',
                'title' => __('general.common.setting'),
            ],
            [
                'title' => __('general.menu.furlough_policy_management.title'),
                'icon' => 'icon icon-e-reader',
                'child' => [
                    [
                        'title' => __('general.menu.furlough_policy_template_management.title'),
                        'url' => route('hr.furlough-policy-template.index'),
                        'active' => Route::is(['hr.furlough-policy-template.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_LIST),
                    ],
                    [
                        'title' => __('general.menu.furlough_policy_management.title'),
                        'url' => route('hr.furlough-policies.index'),
                        'active' => Route::is(['hr.furlough-policies.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_LIST),
                    ],
                ],
            ],
            [
                'title' => __('general.common.contract'),
                'icon' => 'icon icon-form',
                'child' => [
                    [
                        'title' => __('general.menu.contract_type_management.title'),
                        'url' => route('hr.contract_type.index'),
                        'active' => Route::is(['hr.contract_type.*']),
                        'show' => checkPermission(Acl::PERMISSION_CONTRACT_TYPE_LIST),
                    ],
                    [
                        'title' => __('general.menu.contract_management.title'),
                        'url' => route('hr.contract.index'),
                        'active' => Route::is(['hr.contract.*']),
                        'show' => checkPermission(Acl::PERMISSION_CONTRACT_LIST),
                    ],
                ],
            ],
            [
                'title' => __('general.menu.setting_management.title'),
                'icon' => 'icon icon-preferences-circle',
                'child' => [
                    [
                        'title' => __('general.menu.ai_profile_management.manage_ai_profile'),
                        'url' => route('hr.ai_profile.index'),
                        'active' => Route::is(['hr.ai_profile.*']),
                        'show' => checkPermission(Acl::PERMISSION_AI_PROFILE_LIST),
                    ],
                ],
            ],
        ]);
    }

   private function buildMenuPurpose(): void
   {
       $this->menuItems = array_merge($this->menuItems, [
            [
               'type' => 'label',
               'title' => __('general.common.purpose'),
            ],
            [
                'title' => __('general.menu.furlough_management.title'),
                'icon' => 'icon icon-window-add',
                'child' => [
                    [
                        'title' => __('general.menu.furlough_management.manage_furlough'),
                        'url' => route('hr.furlough.index'),
                        'active' => Route::is(['hr.furlough.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_LIST),
                    ],
                ],
            ],
            [
                'title' => __('general.menu.salary_propose_management.title'),
                'icon' => 'icon icon-power-level',
                'child' => [
                    [
                        'title' => __('general.menu.salary_propose_management.manage_salary_propose'),
                        'url' => route('hr.salary-propose.index'),
                        'active' => Route::is(['hr.salary-propose.*']),
                        'show' => checkPermission(Acl::PERMISSION_SALARY_PROPOSE_LIST),
                    ],
                ],
            ],
       ]);
   }

    private function setProperties(): void
    {
        $this->menuItems = [];
    }
}
