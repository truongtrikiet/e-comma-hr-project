<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.ai_profile_management.create_ai_profile') }}
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
            __('general.menu.ai_profile_management.manage_ai_profile') => route('hr.ai_profile.index'),
            __('general.menu.ai_profile_management.create_ai_profile') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('hr.ai_profile.store')"
        :card-title="__('general.menu.ai_profile_management.create_ai_profile')"
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
                            />

                            @if (session('school_name') === config('subdomain.system_main'))
                                <x-form.form-select
                                    :id="'sSchoolSelect'"
                                    :label="__('general.common.school')"
                                    :data-values="$schools"
                                    :select-value-attribute="'id'"
                                    :select-value-label="'name'"
                                    :name="'school_id'"
                                    :multiple="false"
                                    :placeholder="__('general.common.school')"
                                    :isRequired="false"
                                />
                            @else
                                <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                            @endif

                            <x-form.form-select
                                :id="'sProviderSelect'"
                                :label="__('general.common.provider')"
                                :data-values="$provides"
                                :name="'provider'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.provider')"
                                :isRequired="true"
                            />

                            <x-form.form-input
                                :id="'model'"
                                :name="'model'"
                                :label="__('general.common.model') "
                                :placeholder="__('general.common.model') "
                                :isRequired="true"
                            />

                            <x-form.form-input
                                :id="'endpoint'"
                                :name="'endpoint'"
                                :label="__('general.common.endpoint') "
                                :placeholder="__('general.common.endpoint') "
                                :isRequired="true"
                            />

                            <x-form.form-textarea
                                :id="'api_key_encrypted'"
                                :name="'api_key_encrypted'"
                                :label="__('general.common.api_key')"
                                :placeholder="__('general.common.api_key')"
                                :rows="4"
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
