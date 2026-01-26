<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.school_management.school') }}
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
            __('general.menu.school_management.school') => route('admin.school.index'),
            __('general.menu.school_management.edit_school') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.school.update', $school->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.school_management.edit_school')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.form-input
                                :id="'name'"
                                :name="'name'"
                                :label="__('general.common.name') "
                                :placeholder="__('general.common.name') "
                                :isRequired="true"
                                :value="$school->name"
                            />

                            <x-form.form-input
                                :id="'sub_domain'"
                                :name="'sub_domain'"
                                :label="__('general.menu.school_management.domain_school') "
                                :placeholder="__('general.menu.school_management.domain_school') "
                                :isRequired="true"
                                :value="$school->sub_domain"
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
                                :selected="old('status', $school->status->value)"
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
