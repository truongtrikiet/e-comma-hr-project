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
        $this->setProperties();
        $this->buildMenuDashboard();
        $this->buildMenuSettings();
        // $this->buildMenuProduct();
        // $this->buildClientMenu();
        // $this->buildMenuCampaign();
        // $this->buildMenuAddress();
        // $this->buildMenuOrder();
    }

    private function buildMenuDashboard(): void
    {
        $this->menuItems = array_merge($this->menuItems, [
            [
                'type' => 'label',
                'title' => __('general.menu.system_management'),
            ],
            [
                'title' => __('general.menu.dashboard'),
                'icon' => 'icon icon-house-pricing',
                'type' => 'dropdown',
                'child' => [
                    [
                        'title' => __('Dashboard'),
                        'url' => route('admin.dashboard'),
                        'active' => Route::is(['admin.dashboard']),
                        'show' => checkPermissions([Acl::PERMISSION_VIEW_MENU_DASHBOARD]),
                    ],
                ],
            ],
        ]);
    }

    private function buildMenuSettings(): void
    {
        $this->menuItems = array_merge($this->menuItems, [
            [
                'type' => 'label',
                'title' => __('general.menu.hr_management'),
            ],
            [
                'title' => __('general.common.user'),
                'icon' => 'icon icon-single-04-2',
                'child' => [
                    [
                        'title' => __('general.menu.user_management.user'),
                        'url' => route('admin.user.index'),
                        'active' => Route::is(['admin.user.*']),
                        'show' => checkPermissions([Acl::PERMISSION_USER_LIST]),
                    ],
                ],
            ],
        ]);
    }

//    private function buildMenuProduct(): void
//    {
//        $this->menuItems = array_merge($this->menuItems, [
//            [
//                'type' => 'label',
//                'title' => __('Sản phẩm'),
//            ],
//            [
//                'title' => __('general.menu.lucky_draw.title'),
//                'url' => route('admin.lucky-draw.index'),
//                'icon' => 'aperture',
//                'active' => Route::is(['admin.lucky-draw.*']),
//                'show' => checkPermissions([Acl::PERMISSION_LUCKY_DRAW_LIST]),
//                'child' => [],
//            ],
//        ]);
//    }

//    private function buildMenuOrder(): void
//    {
//        $this->menuItems = array_merge($this->menuItems, [
//            [
//                'type' => 'label',
//                'title' => __('Đơn hàng'),
//            ],
//            [
//                'title' => __('general.menu.order_management.title'),
//                'url' => route('admin.order-management.order.index'),
//                'icon' => 'shopping-cart',
//                'active' => Route::is(['admin.order-management.order.*']),
//                'show' => checkPermissions([Acl::PERMISSION_ORDER_LIST]),
//                'child' => [],
//            ],
//        ]);
//    }

//    private function buildClientMenu(): void
//    {
//        $this->menuItems = array_merge($this->menuItems, [
//            [
//                'title' => __('Khách hàng'),
//                'is_show_title_menu' => checkPermissions([Acl::PERMISSION_CLIENT_LIST]),
//            ],
//            [
//                'title' => __('Khách hàng'),
//                'url' => route('admin.client-management.client.index'),
//                'icon' => 'tag',
//                'active' => Route::is(['admin.client-management.client.*']),
//                'show' => checkPermissions([Acl::PERMISSION_CLIENT_LIST]),
//                'child' => [],
//            ],
//        ]);
//    }

//    private function buildMenuCampaign(): void
//    {
//        $this->menuItems = array_merge($this->menuItems, [
//            [
//                'title' => __('Chiến Dịch'),
//                'is_show_title_menu' => checkPermissions([Acl::PERMISSION_CAMPAIGN_LIST]),
//            ],
//            [
//                'title' => __('Chiến Dịch'),
//                'url' => route('admin.campaign-management.campaign.index'),
//                'icon' => 'calendar',
//                'active' => Route::is(['admin.campaign-management.campaign.*', 'admin.campaign-schedule-management.schedule.*', 'admin.campaign-management.*']),
//                'show' => checkPermissions([Acl::PERMISSION_CAMPAIGN_LIST]),
//                'child' => [],
//            ],
//        ]);
//    }

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
