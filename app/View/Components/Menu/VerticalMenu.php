<?php

namespace App\View\Components\Menu;

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
        return view('components.menu.vertical-menu');
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
                        'url' => route('admin.dashboard'),
                        'active' => Route::is(['admin.dashboard']),
                        'show' => checkPermission(Acl::PERMISSION_VIEW_MENU_SUPER_ADMIN) || checkPermission(Acl::PERMISSION_VIEW_MENU_ADMIN),
                    ],
                    [
                        'title' => __('general.menu.school_management.school'),
                        'url' => route('admin.school.index'),
                        'active' => Route::is(['admin.school.*']),
                        'show' => checkPermission(Acl::PERMISSION_SCHOOL_LIST),
                    ],
                    [
                        'title' => __('general.menu.holiday_schedule_management.manage_holiday_schedule'),
                        'url' => route('admin.holiday-schedule.index'),
                        'active' => Route::is(['admin.holiday-schedule.*']),
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
                        'url' => route('admin.user.index'),
                        'active' => Route::is(['admin.user.*']),
                        'show' => checkPermissions([Acl::PERMISSION_USER_LIST]),
                    ],
                    [
                        'title' => __('general.menu.employee_type_management.employee_type'),
                        'url' => route('admin.employee-type.index'),
                        'active' => Route::is(['admin.employee-type.*']),
                        'show' => checkPermissions([Acl::PERMISSION_EMPLOYEE_TYPE_LIST]),
                    ]
                ],
            ],
            [
                'title' => __('general.menu.department_management.title'),
                'icon' => 'icon icon-tag-content',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('general.menu.department_management.department'),
                        'url' => route('admin.department.index'),
                        'active' => Route::is(['admin.department.*']),
                        'show' => checkPermissions([Acl::PERMISSION_DEPARTMENT_LIST]),
                    ],
                ],
            ],
            [
                'title' => __('general.menu.subject_management.title'),
                'icon' => 'icon icon-book-open-2',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('general.menu.subject_management.subject'),
                        'url' => route('admin.subject.index'),
                        'active' => Route::is(['admin.subject.*']),
                        'show' => checkPermissions([Acl::PERMISSION_SUBJECT_LIST]),
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
                        'title' => __('general.menu.furlough_type_management.manage_furlough_type'),
                        'url' => route('admin.furlough-type.index'),
                        'active' => Route::is(['admin.furlough-type.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_TYPE_LIST),
                    ],
                    [
                        'title' => __('general.menu.furlough_policy_template_management.title'),
                        'url' => route('admin.furlough-policy-template.index'),
                        'active' => Route::is(['admin.furlough-policy-template.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_LIST),
                    ],
                    [
                        'title' => __('general.menu.furlough_policy_management.title'),
                        'url' => route('admin.furlough-policies.index'),
                        'active' => Route::is(['admin.furlough-policies.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_LIST),
                    ],
                    [
                        'title' => __('general.menu.school_working_calendar_management.title'),
                        'url' => route('admin.school-working-calendar.index'),
                        'active' => Route::is(['admin.school-working-calendar.*']),
                        'show' => checkPermission(Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_LIST),
                    ],
                ],
            ],
            [
                'title' => __('general.menu.setting_management.title'),
                'icon' => 'icon icon-house-pricing',
                'child' => [
                    [
                        'title' => __('general.menu.role_management.role'),
                        'url' => route('admin.role.index'),
                        'active' => Route::is(['admin.role.*']),
                        'show' => checkPermission(Acl::PERMISSION_ROLE_LIST),
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
                        'url' => route('admin.furlough.index'),
                        'active' => Route::is(['admin.furlough.*']),
                        'show' => checkPermission(Acl::PERMISSION_FURLOUGH_LIST),
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
