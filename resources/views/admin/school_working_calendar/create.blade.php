<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.school_working_calendar_management.school_working_calendar') }}
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
        <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
        <style>
            #general-settings .select2-container--default .select2-search--inline .select2-search__field {
                margin-left: 10px;
            }

            #general-settings .select2-container--default .select2-selection__placeholder,
            #general-settings .select2-container--default .select2-selection__rendered.select2-placeholder {
                margin-left: 10px;
            }
        </style>

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.school_working_calendar_management.school_working_calendar') => route('admin.school-working-calendar.index'),
            __('general.menu.school_working_calendar_management.create_school_working_calendar') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.school-working-calendar.store')"
        :card-title="__('general.menu.school_working_calendar_management.create_school_working_calendar')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">
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

                            <x-form.form-select-multiple
                                :id="'sWorkingDaysSelect'"
                                :label="__('general.common.working_days')"
                                :data-values="$daysOfWeek"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'working_days'"
                                :multiple="true"
                                :placeholder="__('general.common.working_days')"
                                :isRequired="true"
                            />

                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.form-input
                                        :id="'sWorkingHoursStartInput'"
                                        :label="__('general.common.working_hours_start')"
                                        :name="'working_hours_start'"
                                        :type="'time'"
                                        :placeholder="__('general.common.working_hours_start')"
                                        :isRequired="true"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.form-input
                                        :id="'sWorkingHoursEndInput'"
                                        :label="__('general.common.working_hours_end')"
                                        :name="'working_hours_end'"
                                        :type="'time'"
                                        :placeholder="__('general.common.working_hours_end')"
                                        :isRequired="true"
                                    />
                                </div>
                            </div>
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
        <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('js/plugins-init/select2-init.js') }}"></script>
    </x-slot:footerFiles>
</x-base-layout>
