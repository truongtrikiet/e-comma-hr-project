<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_policy_template_management.furlough_policy_template') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
        <link href="{{ asset('vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <!-- Clockpicker -->
        <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
        <!-- asColorpicker -->
        <link href="{{ asset('vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
        <!-- Material color picker -->
        <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
        <!-- Pick date -->
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.date.css') }}">
        <!-- Custom Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/filepond.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_policy_template_management.manage_furlough_policy_template') => route('hr.furlough-policy-template.index'),
            __('general.menu.furlough_policy_template_management.create_furlough_policy_template') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('hr.furlough-policy-template.store')"
        :card-title="__('general.menu.furlough_policy_template_management.create_furlough_policy_template')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <x-custom.locale-tab
                                :id="'name'"
                                :fields="[
                                    [
                                        'type' => 'input',
                                        'name' => 'name',
                                        'label' => __('general.common.name'),
                                        'placeholder' => __('general.common.name'),
                                        'value' => null,
                                        'isRequired' => true,
                                    ],
                                ]"
                            />

                            <x-form.form-textarea
                                :id="'description'"
                                :label="__('general.common.description')"
                                :name="'description'"
                                :placeholder="__('general.common.description')"
                                :isRequired="false"
                            />

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'accrual_per_month'"
                                            :label="__('general.common.accrual_per_month')"
                                            :name="'accrual_per_month'"
                                            :type="'number'"
                                            :value="1"
                                            :isRequired="true"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'max_days'"
                                            :label="__('general.common.max_days')"
                                            :name="'max_days'"
                                            :type="'number'"
                                            :value="1"
                                            :isRequired="true"
                                        />
                                    </div>
                                </div>
                            </div>

                            <x-form.form-select
                                :id="'sIsPaidSelect'"
                                :label="__('general.common.paid')"
                                :data-values="$isPaid"
                                :name="'is_paid'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.paid')"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sCarryForwardSelect'"
                                :label="__('general.common.carry_forward')"
                                :data-values="[['value' => 1, 'label' => __('general.common.yes')], ['value' => 0, 'label' => __('general.common.no')]]"
                                :name="'carry_forward'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.carry_forward')"
                                :isRequired="true"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="mb-3">
                    <x-buttons.submit :label="__('general.common.complete')"/>
                </div>
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        
    </x-slot:footerFiles>
</x-hr.base-layout>
