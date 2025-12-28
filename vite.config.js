import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [

                /**
                 * =======================
                 *      Assets JS Files
                 * =======================
                 */

                // js
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/custom.min.js',
                'resources/js/layout-compact-nav.js',
                'resources/js/layout-dark.js',
                'resources/js/layout-fixed-header.js',
                'resources/js/layout-fixed-nav.js',
                'resources/js/layout-full-nav.js',
                'resources/js/layout-light.js',
                'resources/js/layout-mini-nav.js',
                'resources/js/layout-rtl.js',
                'resources/js/quixnav-init.js',
                'resources/js/settings.js',
                'resources/js/styleSwitcher.js',

                //js/dashboard
                'resources/js/dashboard/1.js',
                'resources/js/dashboard/dashboard-1.js',
                'resources/js/dashboard/dashboard-2.js',

                //js/plugins-init
                'resources/js/plugins-init/amchart-init.js',
                'resources/js/plugins-init/blockui-init.js',
                'resources/js/plugins-init/bootstrap-editable-init.js',
                'resources/js/plugins-init/bootstrap-multiselect-init.js',
                'resources/js/plugins-init/bootstrap-tagsinput-init.js',
                'resources/js/plugins-init/bootstrap-touchpin-init.js',
                'resources/js/plugins-init/bs-daterange-picker-init.js',
                'resources/js/plugins-init/bs-notify-init.js',
                'resources/js/plugins-init/bs-select-init.js',
                'resources/js/plugins-init/c3-init.js',
                'resources/js/plugins-init/chartjs-init.js',
                'resources/js/plugins-init/chartist-init.js',
                'resources/js/plugins-init/circle-progress-init.js',
                'resources/js/plugins-init/clipboard-init.js',
                'resources/js/plugins-init/clock-picker-init.js',
                'resources/js/plugins-init/color-picker-init.js',
                'resources/js/plugins-init/counter-init.js',
                'resources/js/plugins-init/counterup-init.js',
                'resources/js/plugins-init/cropperjs-init.js',
                'resources/js/plugins-init/datamap-init.js',
                'resources/js/plugins-init/datatables.init.js',
                'resources/js/plugins-init/datedropper-init.js',
                'resources/js/plugins-init/dropify-init.js',
                'resources/js/plugins-init/easy-pie-chart-init.js',
                'resources/js/plugins-init/echarts-init.js',
                'resources/js/plugins-init/editor-ck-init.js',
                'resources/js/plugins-init/editor-tinymice-init.js',
                'resources/js/plugins-init/flot-init.js',
                'resources/js/plugins-init/footable-init.js',
                'resources/js/plugins-init/form-bootstrap-validate-init.js',
                'resources/js/plugins-init/fullcalendar-init.js',
                'resources/js/plugins-init/highcharts-init.js',
                'resources/js/plugins-init/jquery-asColorPicker.init.js',
                'resources/js/plugins-init/jquery-steps-init.js',
                'resources/js/plugins-init/jquery.bootgrid-init.js',
                'resources/js/plugins-init/jquery.validate-init.js',
                'resources/js/plugins-init/jqvmap-init.js',
                'resources/js/plugins-init/jsgrid-init.js',
                'resources/js/plugins-init/justgage.init.js',
                'resources/js/plugins-init/knob.init.js',
                'resources/js/plugins-init/material-date-picker-init.js',
                'resources/js/plugins-init/morris-init.js',
                'resources/js/plugins-init/nestable-init.js',
                'resources/js/plugins-init/nouislider-init.js',
                'resources/js/plugins-init/pickadate-init.js',
                'resources/js/plugins-init/piety-init.js',
                'resources/js/plugins-init/pignose.init.js',
                'resources/js/plugins-init/quill-init.js',
                'resources/js/plugins-init/rickshaw-init.js',
                'resources/js/plugins-init/select2-init.js',
                'resources/js/plugins-init/sparkline-init.js',
                'resources/js/plugins-init/summernote-init.js',
                'resources/js/plugins-init/sweetalert-init.js',
                'resources/js/plugins-init/sweetalert.init.js',
                'resources/js/plugins-init/switchery-init.js',
                'resources/js/plugins-init/timepicki-init.js',
                'resources/js/plugins-init/toastr-init.js',
                'resources/js/plugins-init/typehead.js-init.js',
                'resources/js/plugins-init/ui-slider-init.js',
                'resources/js/plugins-init/webticker-init.js',
                'resources/js/plugins-init/widgets-script-init.js',

                /**
                 * =======================
                 *      Assets CSS Files
                 * =======================
                 */

                // CSS
                'resources/css/app.css',
                'resources/css/perfect-scrollbar.css',
                'resources/css/style.css',

                'public/assets/css/lib/font-awesome.min.css',
                'public/assets/css/lib/themify-icons.css',
                'public/assets/css/lib/bootstrap.min.css',
                'public/assets/css/lib/helper.css',
                'public/assets/css/style.css',

                /**
                 * =======================
                 *      Assets SCSS Files
                 * =======================
                 */

                // SCSS
                
                'resources/scss/main.scss',
                'resources/scss/_preloader.scss',

                // SCSS - Abstracts
                'resources/scss/abstracts/_abstracts.scss',
                'resources/scss/abstracts/_bs-custom.scss',
                'resources/scss/abstracts/_inheritance.scss',
                'resources/scss/abstracts/_maps.scss',
                'resources/scss/abstracts/_mixin.scss',
                'resources/scss/abstracts/_variable.scss',

                // SCSS - Base
                'resources/scss/base/_base.scss',
                'resources/scss/base/_colors.scss',
                'resources/scss/base/_custom-grid.scss',
                'resources/scss/base/_fonts.scss',
                'resources/scss/base/_helper.scss',
                'resources/scss/base/_reset.scss',

                // SCSS - Components
                'resources/scss/components/_components.scss',

                // SCSS - components - app
                'resources/scss/components/app/_app-calender-date.scss',
                'resources/scss/components/app/_app-calender-event.scss',
                'resources/scss/components/app/_apps.scss',
                'resources/scss/components/app/_chat.scss',
                'resources/scss/components/app/_email.scss',
                'resources/scss/components/app/_invoice.scss',
                'resources/scss/components/app/_profile.scss',

                // SCSS - components - charts
                'resources/scss/components/charts/_chart-amchart.scss',
                'resources/scss/components/charts/_chart-c3.scss',
                'resources/scss/components/charts/_chart-chartist.scss',
                'resources/scss/components/charts/_chart-chartjs.scss',
                'resources/scss/components/charts/_chart-flot.scss',
                'resources/scss/components/charts/_chart-highcharts.scss',
                'resources/scss/components/charts/_chart-morris.scss',
                'resources/scss/components/charts/_chart-sparkline.scss',
                'resources/scss/components/charts/_charts.scss',
                'resources/scss/components/charts/_easy-pie-chart.scss',
                'resources/scss/components/charts/_echarts.scss',

                // SCSS - components - forms
                'resources/scss/components/forms/_form-addons-cropper.scss',
                'resources/scss/components/forms/_form-addons-select2.scss',
                'resources/scss/components/forms/_form-advance-taginput.scss',
                'resources/scss/components/forms/_form-basic.scss',
                'resources/scss/components/forms/_form-checkbox.scss',
                'resources/scss/components/forms/_form-dropzone.scss',
                'resources/scss/components/forms/_form-editor-quill.scss',
                'resources/scss/components/forms/_form-pickers.scss',
                'resources/scss/components/forms/_form-radio-button.scss',
                'resources/scss/components/forms/_form-steps.scss',
                'resources/scss/components/forms/_form-summernote.scss',
                'resources/scss/components/forms/_form-switch.scss',
                'resources/scss/components/forms/_form-touchspin.scss',
                'resources/scss/components/forms/_form-validation.scss',
                'resources/scss/components/forms/_forms.scss',

                // SCSS - components - Ico
                'resources/scss/components/ico/_buy-sell.scss',
                'resources/scss/components/ico/_exchange.scss',
                'resources/scss/components/ico/_gateway.scss',
                'resources/scss/components/ico/_ico.scss',
                'resources/scss/components/ico/_marketcap.scss',
                'resources/scss/components/ico/_ticker.scss',
                'resources/scss/components/ico/_trading.scss',
                'resources/scss/components/ico/_transaction.scss',
                'resources/scss/components/ico/_wallet.scss',

                // SCSS - components - Map
                'resources/scss/components/map/_map-datamap.scss',
                'resources/scss/components/map/_map-jqvmap.scss',
                'resources/scss/components/map/_maps.scss',

                // SCSS - components - tables
                'resources/scss/components/tables/_table-basic.scss',
                'resources/scss/components/tables/_table-bootgrid.scss',
                'resources/scss/components/tables/_table-datatable.scss',
                'resources/scss/components/tables/_table-footable.scss',
                'resources/scss/components/tables/_table-jsgrid.scss',
                'resources/scss/components/tables/_table.scss',

                // SCSS - components - uc
                'resources/scss/components/uc/_addons.scss',
                'resources/scss/components/uc/_jqvmap.scss',
                'resources/scss/components/uc/_perfect-scroll.scss',
                'resources/scss/components/uc/_uc-blockui.scss',
                'resources/scss/components/uc/_uc-bootstrap-select.scss',
                'resources/scss/components/uc/_uc-clipboard.scss',
                'resources/scss/components/uc/_uc-counterup.scss',
                'resources/scss/components/uc/_uc-horizontal-timeline.scss',
                'resources/scss/components/uc/_uc-nestable.scss',
                'resources/scss/components/uc/_uc-noui-slider.scss',
                'resources/scss/components/uc/_uc-pignose-calender.scss',
                'resources/scss/components/uc/_uc-tagsinput.scss',
                'resources/scss/components/uc/_uc-ticker.scss',
                'resources/scss/components/uc/_uc-toastr.scss',
                'resources/scss/components/uc/_uc-typeahead.scss',
                'resources/scss/components/uc/_uc-weather.scss',

                // SCSS - components - ui
                'resources/scss/components/ui/_interfaces.scss',
                'resources/scss/components/ui/_ui-accordion.scss',
                'resources/scss/components/ui/_ui-alert.scss',
                'resources/scss/components/ui/_ui-badge.scss',
                'resources/scss/components/ui/_ui-breadcrumb.scss',
                'resources/scss/components/ui/_ui-button.scss',
                'resources/scss/components/ui/_ui-card.scss',
                'resources/scss/components/ui/_ui-carousel.scss',
                'resources/scss/components/ui/_ui-dropdown.scss',
                'resources/scss/components/ui/_ui-grid.scss',
                'resources/scss/components/ui/_ui-label.scss',
                'resources/scss/components/ui/_ui-list-group.scss',
                'resources/scss/components/ui/_ui-media.scss',
                'resources/scss/components/ui/_ui-menu.scss',
                'resources/scss/components/ui/_ui-modal.scss',
                'resources/scss/components/ui/_ui-pagination.scss',
                'resources/scss/components/ui/_ui-popover.scss',
                'resources/scss/components/ui/_ui-preloader.scss',
                'resources/scss/components/ui/_ui-pricing.scss',
                'resources/scss/components/ui/_ui-progressbar.scss',
                'resources/scss/components/ui/_ui-ribbon.scss',
                'resources/scss/components/ui/_ui-scrollbar.scss',
                'resources/scss/components/ui/_ui-step.scss',
                'resources/scss/components/ui/_ui-tab.scss',
                'resources/scss/components/ui/_ui-timeline.scss',
                'resources/scss/components/ui/_ui-tooltip.scss',

                // SCSS - components - widget                
                'resources/scss/components/widget/_widget-chart.scss',
                'resources/scss/components/widget/_widget-social.scss',
                'resources/scss/components/widget/_widget-stat.scss',
                'resources/scss/components/widget/_widget-todo-list.scss',
                'resources/scss/components/widget/_widgets.scss',

                // SCSS - layout
                // 'resources/scss/layout/_layout.scss',

                // SCSS - layout - footer
                // 'resources/scss/layout/footer/_footer.scss',

                // SCSS - layout - header
                // 'resources/scss/layout/header/_header-global.scss',
                // 'resources/scss/layout/header/_header-left.scss',
                // 'resources/scss/layout/header/_header-right.scss',
                // 'resources/scss/layout/header/_header.scss',

                // SCSS - layout - header - nav-header
                // 'resources/scss/layout/header/nav-header/_nav-control.scss',
                // 'resources/scss/layout/header/nav-header/_nav-header.scss',

                // SCSS - layout - rtl
                // 'resources/scss/layout/rtl/_rtl-footer.scss',
                // 'resources/scss/layout/rtl/_rtl-global.scss',
                // 'resources/scss/layout/rtl/_rtl-header.scss',
                // 'resources/scss/layout/rtl/_rtl-nav-header.scss',
                // 'resources/scss/layout/rtl/_rtl-reset.scss',
                // 'resources/scss/layout/rtl/_rtl-sidebar-right.scss',
                // 'resources/scss/layout/rtl/_rtl-sidebar.scss',
                // 'resources/scss/layout/rtl/_rtl.scss',

                // // SCSS - layout - sidebar
                // 'resources/scss/layout/sidebar/_mega-menu.scss',
                // 'resources/scss/layout/sidebar/_sidebar-bg.scss',
                // 'resources/scss/layout/sidebar/_sidebar-compact-nav.scss',
                // 'resources/scss/layout/sidebar/_sidebar-full.scss',
                // 'resources/scss/layout/sidebar/_sidebar-global.scss',
                // 'resources/scss/layout/sidebar/_sidebar-horizontal.scss',
                // 'resources/scss/layout/sidebar/_sidebar-icon-hover.scss',
                // 'resources/scss/layout/sidebar/_sidebar-mini-nav.scss',
                // 'resources/scss/layout/sidebar/_sidebar-modern.scss',
                // 'resources/scss/layout/sidebar/_sidebar-overlay.scss',
                // 'resources/scss/layout/sidebar/_sidebar-profile.scss',
                // 'resources/scss/layout/sidebar/_sidebar-right.scss',
                // 'resources/scss/layout/sidebar/_sidebar-vertical-nav.scss',
                // 'resources/scss/layout/sidebar/_sidebar.scss',

                // // SCSS - layout - theme
                // 'resources/scss/layout/theme/_theme-bg.scss',
                // 'resources/scss/layout/theme/_theme-boxed.scss',
                // 'resources/scss/layout/theme/_theme-wide-boxed.scss',
                // 'resources/scss/layout/theme/_theme.scss',

                // // SCSS - layout - typography
                // 'resources/scss/layout/typography/_helvetica.scss',
                // 'resources/scss/layout/typography/_opensans.scss',
                // 'resources/scss/layout/typography/_poppins.scss',
                // 'resources/scss/layout/typography/_roboto.scss',
                // 'resources/scss/layout/typography/_typography.scss',

                // // SCSS - layout - version-dark
                // 'resources/scss/layout/version-dark/_dark-footer.scss',
                // 'resources/scss/layout/version-dark/_dark-global.scss',
                // 'resources/scss/layout/version-dark/_dark-header.scss',
                // 'resources/scss/layout/version-dark/_dark-left-sidebar.scss',
                // 'resources/scss/layout/version-dark/_dark-nav-header.scss',
                // 'resources/scss/layout/version-dark/_dark-preloader.scss',
                // 'resources/scss/layout/version-dark/_dark-reset.scss',
                // 'resources/scss/layout/version-dark/_dark-right-sidebar.scss',
                // 'resources/scss/layout/version-dark/_main.scss',

                // // SCSS - layout - version-transparent
                // 'resources/scss/layout/version-transparent/_main.scss',
                // 'resources/scss/layout/version-transparent/_transparent-footer.scss',
                // 'resources/scss/layout/version-transparent/_transparent-global.scss',
                // 'resources/scss/layout/version-transparent/_transparent-header.scss',
                // 'resources/scss/layout/version-transparent/_transparent-left-sidebar.scss',
                // 'resources/scss/layout/version-transparent/_transparent-nav-header.scss',
                // 'resources/scss/layout/version-transparent/_transparent-preloader.scss',
                // 'resources/scss/layout/version-transparent/_transparent-reset.scss',
                // 'resources/scss/layout/version-transparent/_transparent-right-sidebar.scss',

                // // SCSS - pages
                // 'resources/scss/pages/_ecom-billing.scss',
                // 'resources/scss/pages/_ecom-checkout.scss',
                // 'resources/scss/pages/_ecom-customer-details.scss',
                // 'resources/scss/pages/_ecom-customers.scss',
                // 'resources/scss/pages/_ecom-invoice.scss',
                // 'resources/scss/pages/_ecom-order-details.scss',
                // 'resources/scss/pages/_ecom-product-detail.scss',
                // 'resources/scss/pages/_ecom-product-grid.scss',
                // 'resources/scss/pages/_ecom-product-list.scss',
                // 'resources/scss/pages/_ecom-product-order.scss',
                // 'resources/scss/pages/_ecom-shopping-cart.scss',
                // 'resources/scss/pages/_homepage.scss',
                // 'resources/scss/pages/_page-auth.scss',
                // 'resources/scss/pages/_page-error.scss',
                // 'resources/scss/pages/_page-pricing.scss',
                // 'resources/scss/pages/_page-timeline.scss',
                // 'resources/scss/pages/_pages.scss',

                // Vendor

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
