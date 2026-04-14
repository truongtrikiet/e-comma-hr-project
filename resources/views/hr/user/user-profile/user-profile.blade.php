<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.user_management.user') }}
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
            strong {
                width: 140px;
                display: inline-block;
                font-weight: bold;
            }
        </style>

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.dashboard') => route('hr.dashboard'),
            __('general.common.user_profile') => '',
        ]"
    />

    <div class="row">
        <div class="col-lg-12">
            <div class="profile">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo"></div>
                        <div class="profile-photo">
                            <img src="{{ $user->avatar_url ?? asset('images/avatar/1.png') }}" class="img-fluid rounded-circle" alt="Avatar">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-xl-4 col-sm-4 border-right-1 prf-col">
                                        <div class="profile-name">
                                            <h4 class="text-primary">{{ $user->name ?? 'N/A' }}</h4>
                                            <p>{{ $user->userProfile->employee_code ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-4 border-right-1 prf-col">
                                        <div class="profile-email">
                                            <h4 class="text-muted">{{ $user->email ?? 'N/A' }}</h4>
                                            <p>Email</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-sm-4 prf-col">
                                        <div class="profile-call">
                                            <h4 class="text-muted">{{ $user->phone_number ?? 'N/A' }}</h4>
                                            <p>Phone No.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Details</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.last_name') }}:</strong> {{ $user->last_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.first_name') }}:</strong> {{ $user->first_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.email') }}:</strong> {{ $user->email ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.phone_number') }}:</strong> {{ $user->phone_number ?? 'N/A' }}
                        </div>
                    </div>
                    <h5 class="mb-2 mt-3">{{ __('general.common.personal_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.employee_code') }}:</strong> {{ $user->userProfile->employee_code ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.date_of_birth') }}:</strong> {{ customDate($user?->userProfile?->date_of_birth ?? 'N/A') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.gender') }}:</strong> {{ $user->userProfile->gender->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.position') }}:</strong> {{ $user->userProfile?->position ?? 'N/A' }}
                        </div>
                    </div>
                    <h5 class="mb-2 mt-3">{{ __('general.common.work_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.school') }}:</strong> {{ $user->school->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.entry_date') }}:</strong> {{ customDate($user?->userProfile?->entry_date ?? 'N/A') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.employment_status') }}:</strong> {{ $user->userProfile->employment_status->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.subject') }}:</strong> {{ $user->userProfile->subject_id ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.role') }}:</strong> {{ $user->roles->first()->name ?? 'N/A' }}
                        </div>
                    </div>
                    <h5 class="mb-2 mt-3">{{ __('general.common.system_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.status') }}:</strong> {{ $user->status_name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.created_at') }}:</strong> {{ customDateFormat($user->created_at ?? 'N/A') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('general.common.updated_at') }}:</strong> {{ customDateFormat($user->updated_at ?? 'N/A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:footerFiles>
        

    </x-slot:footerFiles>
</x-hr.base-layout>
