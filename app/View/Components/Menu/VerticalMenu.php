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
        // $this->buildMenuAddress();
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
                        'show' => checkPermission(Acl::PERMISSION_VIEW_MENU_DASHBOARD),
                    ],
                    [
                        'title' => __('general.menu.school_management.school'),
                        'url' => route('admin.school.index'),
                        'active' => Route::is(['admin.school.*']),
                        'show' => checkPermission(Acl::PERMISSION_SCHOOL_LIST),
                    ]
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
               'title' => __('general.menu.setting_management.title'),
               'icon' => 'icon icon-house-pricing',
               'child' => [
                    [
                        'title' => __('general.menu.role_management.role'),
                        'url' => route('admin.role.index'),
                        'active' => Route::is(['admin.role.*']),
                        'show' => checkPermission(Acl::PERMISSION_PERMISSION_MANAGE),
                    ],
               ],
           ],
       ]);
   }

//    private function buildMenuAddress(): void
//    {
//        $this->menuItems = array_merge($this->menuItems, [
//            [
//                'title' => __('Địa chỉ'),
//                'is_show_title_menu' => checkPermissions([Acl::PERMISSION_CAMPAIGN_LIST]),
//            ],
//            [
//                'title' => __('Khu vực'),
//                'url' => route('admin.area.index'),
//                'icon' => 'globe',
//                'active' => Route::is(['admin.area.*']),
//                'show' => $this->isMainAgency && checkPermissions([Acl::PERMISSION_AREA_LIST]),
//                'child' => [],
//            ],
//        ]);
//    }

    private function setProperties(): void
    {
        $this->menuItems = [];
    }
}
