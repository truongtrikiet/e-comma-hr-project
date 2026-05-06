<x-staff.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.salary_propose_management.create_salary_propose') }}
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
            __('general.menu.salary_propose_management.title') => route('staff.salary-propose.index'),
            __('general.menu.salary_propose_management.create_salary_propose') => '',
        ]"
    />

    <div class="row">
        <div class="col-lg-8">
            <div class="mb-3">
                <h4 class="mb-4">{{ __('general.common.information') }}</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.name') }}:</h6></label>
                            {{ $salaryPropose->user?->name ?? '-' }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.email') }}:</h6></label>
                            {{ $salaryPropose->user?->email ?? '-' }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.gross_amount') }}:</h6></label>
                            {{ number_format($salaryPropose->proposed_gross_amount ?? 0) }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.reason') }}:</h6></label>
                            {{ $salaryPropose->reason ?? '-' }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.effective_date') }}:</h6></label>
                            {{ customDateFormat($salaryPropose->effective_date ?? '-') }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.ends_at') }}:</h6></label>
                            {{ customDateFormat($salaryPropose->ends_at ?? '-') }}
                        </div>
                        <div class="mb-2">
                            <label><h6>{{ __('general.common.status') }}:</h6></label>
                            @php
                                $statusValue = $salaryPropose->status?->value;
                                $statusName = \App\Enum\SalaryStatus::getNameByValue($statusValue) ?? '-';
                                $badge = $salaryPropose->status?->getBadge();
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ $statusName }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:footerFiles>
        <script>
            
        </script>
    
    </x-slot:footerFiles>
</x-staff.base-layout>
