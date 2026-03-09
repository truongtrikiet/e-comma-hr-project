<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_management.edit_furlough') }}
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

        <style>
            h6 {
                width: 160px;
                display: inline-block;
            }
        </style>

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_management.manage_furlough') => route('admin.furlough.index'),
            __('general.menu.furlough_management.edit_furlough') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.furlough.approved', $furlough->id)"
        :formMethod="'PUT'"
        :card-title="__('general.menu.furlough_management.edit_furlough')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h4 class="mb-4">{{ __('general.common.purpose_status') }}</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.form-select
                                :id="'sFurloughStatus'"
                                :label="__('general.common.status')"
                                :data-values="App\Enum\FurloughStatus::options()"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'furlough_status'"
                                :multiple="false"
                                :placeholder="__('general.common.status')"
                                :selected="$furlough->furlough_status->value"
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

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.furlough.approved', $furlough->id)"
        :formMethod="'PUT'"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h4 class="mb-4">{{ __('general.common.information') }}</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.name') }}:</h6></label>
                                {{ $furlough->user?->name ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.email') }}:</h6></label>
                                {{ $furlough->user?->email ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.school') }}:</h6></label>
                                {{ $furlough->school?->name ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.furlough_type_name') }}:</h6></label>
                                {{ $furlough->furloughType?->localeName ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.reason') }}:</h6></label>
                                {{ $furlough->reason ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.furlough_duration_type') }}:</h6></label>
                                {{ \App\Enum\DurationType::getNameByValue($furlough?->duration_type->value ?? '-') }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.furlough_half_day_session') }}:</h6></label>
                                {{ $furlough->half_day_session ? \App\Enum\HalfDaySession::getNameByValue($furlough->half_day_session->value) : '-' }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.start_time') }}:</h6></label>
                                {{ customDateFormat($furlough->start_time ?? '-') }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.end_time') }}:</h6></label>
                                {{ customDateFormat($furlough->end_time ?? '-') }}
                            </div>
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.status') }}:</h6></label>
                                @php
                                    $statusValue = $furlough->furlough_status?->value;
                                    $statusName = \App\Enum\FurloughStatus::getNameByValue($statusValue) ?? '-';
                                    $badge = $furlough->furlough_status?->getBadge();
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $statusName }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        <script>
            
        </script>
        
    </x-slot:footerFiles>
</x-base-layout>
