<x-staff.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_management.create_furlough') }}
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
            __('general.menu.furlough_management.manage_furlough') => route('staff.furlough.index'),
            __('general.menu.furlough_management.create_furlough') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('staff.furlough.store')"
        :card-title="__('general.menu.furlough_management.create_furlough')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h4 class="mb-4">{{ __('general.common.information') }}</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.form-select
                                :id="'sFurloughType'"
                                :label="__('general.common.furlough_type_name')"
                                :data-values="$furloughTypes"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'furlough_type_id'"
                                :multiple="false"
                                :placeholder="__('general.menu.furlough_type_management.title')"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sDurationType'"
                                :label="__('general.common.furlough_duration_type')"
                                :data-values="App\Enum\DurationType::options(true)"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'duration_type'"
                                :multiple="false"
                                :placeholder="__('general.common.furlough_duration_type')"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sHalfDaySession'"
                                :label="__('general.common.furlough_half_day_session')"
                                :data-values="App\Enum\HalfDaySession::options(true)"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'half_day_session'"
                                :multiple="false"
                                :placeholder="__('general.common.furlough_half_day_session')"
                                :is-filter="true"
                            />

                            <x-form.form-input
                                :id="'sStartTime'"
                                :label="__('general.common.start_time')"
                                :name="'start_time'"
                                type="datetime-local"
                                :placeholder="__('general.common.start_time')"
                                :isRequired="true"
                                :is-filter="false"
                            />

                            <x-form.form-input
                                :id="'sEndTime'"
                                :label="__('general.common.end_time')"
                                :name="'end_time'"
                                type="datetime-local"
                                :placeholder="__('general.common.end_time')"
                                :isRequired="true"
                                :is-filter="false"
                            />

                            <x-form.form-textarea
                                :id="'sReason'"
                                :label="__('general.common.reason')"
                                :name="'reason'"
                                :placeholder="__('general.common.reason')"
                                :isRequired="false"
                                :is-filter="false"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sUseBalanceSelect'"
                                :label="__('general.common.use_balance')"
                                :data-values="$useBalance"
                                :name="'use_balance'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.use_balance')"
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
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const duration = document.getElementById('sDurationType');
                const halfDayWrapper = document.getElementById('sHalfDaySession').closest('.form-group');

                function toggleHalfDay() {
                    if (duration.value == 1 || duration.value === "") {
                        halfDayWrapper.style.display = 'none';
                    } else {
                        halfDayWrapper.style.display = 'block';
                    }
                }

                duration.addEventListener('change', toggleHalfDay);

                toggleHalfDay();
            });
        </script>
        
    </x-slot:footerFiles>
</x-staff.base-layout>
