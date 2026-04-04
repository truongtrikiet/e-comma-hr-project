<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_type_management.furlough_type') }}
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
            __('general.menu.furlough_type_management.manage_furlough_type') => route('admin.furlough-type.index'),
            __('general.menu.furlough_type_management.edit_furlough_type') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.furlough-type.update', $furloughType->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.furlough_type_management.edit_furlough_type')"
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
                                        'value' => $furloughType->getTranslations('name'),
                                        'isRequired' => true,
                                    ],
                                ]"
                            />

                            <x-form.form-select
                                :id="'sStatusSelect'"
                                :label="__('general.common.status')"
                                :data-values="$statuses"
                                :name="'status'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.status')"
                                :isRequired="true"
                                :selected="old('status', $furloughType->status->value)"
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
</x-base-layout>
