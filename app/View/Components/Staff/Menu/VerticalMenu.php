<?php

namespace App\View\Components\Staff\Menu;

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
        return view('components.staff.menu.vertical-menu');
    }

    private function generateMenu(): void
    {
        $this->isMainAgency = session('school_name') === config('subdomain.agency_main');
        $this->setProperties();
        $this->buildMenuDashboard();
        $this->buildMainMenu();
        $this->buildMenuPurpose();
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
                        'url' => route('staff.dashboard'),
                        'active' => Route::is(['staff.dashboard']),
                        'show' => checkPermission(Acl::PERMISSION_VIEW_MENU_STAFF) || checkPermission(Acl::PERMISSION_VIEW_MENU_TEACHER),
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
                        'url' => route('staff.user.index'),
                        'active' => Route::is(['staff.user.*']),
                        'show' => checkPermissions([Acl::PERMISSION_USER_LIST]),
                    ],
                ],
            ],
            // [
            //     'title' => __('general.menu.department_management.title'),
            //     'icon' => 'icon icon-tag-content',
            //     'type' => 'dropdown',
            //     'child' => [
            //         [
            //             'title' => __('general.menu.department_management.department'),
            //             'url' => route('admin.department.index'),
            //             'active' => Route::is(['admin.department.*']),
            //             'show' => checkPermissions([Acl::PERMISSION_DEPARTMENT_LIST]),
            //         ],
            //     ],
            // ],
            // [
            //     'title' => __('general.menu.subject_management.title'),
            //     'icon' => 'icon icon-book-open-2',
            //     'type' => 'dropdown',
            //     'child' => [
            //         [
            //             'title' => __('general.menu.subject_management.subject'),
            //             'url' => route('admin.subject.index'),
            //             'active' => Route::is(['admin.subject.*']),
            //             'show' => checkPermissions([Acl::PERMISSION_SUBJECT_LIST]),
            //         ],
            //     ],
            // ],
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
                        'url' => route('staff.furlough.index'),
                        'active' => Route::is(['staff.furlough.*']),
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
