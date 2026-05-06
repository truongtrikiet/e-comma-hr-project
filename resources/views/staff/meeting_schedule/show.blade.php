<x-staff.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.meeting_schedule_management.view_meeting_schedule') }}
    </x-slot:pageTitle>

    <x-slot:headerFiles>
        <link href="{{ asset('vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <style>
            h6 { width: 160px; display: inline-block; }
        </style>
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.meeting_schedule_management.manage_meeting_schedule') => route('staff.meeting-schedule.index'),
            __('general.menu.meeting_schedule_management.view_meeting_schedule') => '',
        ]"
    />

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ __('general.common.information') }}</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <label><h6>{{ __('general.common.title') }}:</h6></label>
                                {{ $meetingSchedule->title ?? '-' }}
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.description') }}:</h6></label>
                                {{ $meetingSchedule->description ?? '-' }}
                            </div>

                            <div class="mb-2 d-flex align-items-start">
                                <label class="me-3"><h6>{{ __('general.common.meeting_target') }}:</h6></label>
                                <div class="flex-grow-1">
                                    @if($meetingSchedule->targets && $meetingSchedule->targets->isNotEmpty())
                                        <div class="d-flex flex-wrap align-items-center">
                                            @foreach($meetingSchedule->targets as $t)
                                                @php
                                                    $type = $t->target_type?->value ?? $t->target_type;
                                                    $label = \App\Enum\MeetingTargetType::getNameByValue($type) ?: '-';
                                                    switch ($type) {
                                                        case \App\Enum\MeetingTargetType::USER->value:
                                                            $display = optional(\App\Models\User::find($t->target_id))->name ?? ('#' . $t->target_id);
                                                            break;
                                                        case \App\Enum\MeetingTargetType::DEPARTMENT->value:
                                                            $display = optional(\App\Models\Department::find($t->target_id))->name ?? ('#' . $t->target_id);
                                                            break;
                                                        case \App\Enum\MeetingTargetType::SCHOOL->value:
                                                            $display = optional(\App\Models\School::find($t->target_id))->name ?? ('#' . $t->target_id);
                                                            break;
                                                        default:
                                                            $display = $t->target_id;
                                                    }
                                                @endphp

                                                <div class="me-4 mb-1">
                                                    <label class="text-muted mb-0">{{ $label }}:</label>
                                                    <div class="d-inline">{{ $display }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.start_time') }}:</h6></label>
                                {{ customDateFormat($meetingSchedule->start_time ?? '-') }}
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.end_time') }}:</h6></label>
                                {{ customDateFormat($meetingSchedule->end_time ?? '-') }}
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.status') }}:</h6></label>
                                @php
                                    $statusValue = $meetingSchedule->status?->value ?? $meetingSchedule->status;
                                    $statusName = \App\Enum\MeetingScheduleStatus::getNameByValue($statusValue) ?? '-';
                                    $badge = $meetingSchedule->status?->getBadge() ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ $statusName }}</span>
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.school') }}:</h6></label>
                                {{ $meetingSchedule->school?->name ?? '-' }}
                            </div>

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.created_by') }}:</h6></label>
                                {{ $meetingSchedule->user?->name ?? '-' }}
                            </div>

                        </div>
                    </div>
                </div>

                </div>
                <div class="card-footer">
                    @can(\App\Acl\Acl::PERMISSION_MEETING_SCHEDULE_EDIT)
                        <x-buttons.button-link :label="__('general.common.edit')" :url="route('staff.meeting-schedule.edit', $meetingSchedule->id)" />
                    @endcan
                    <x-buttons.button-link :label="__('general.common.back')" :url="route('staff.meeting-schedule.index')" />
                </div>
            </div>
        </div>
    </div>

</x-staff.base-layout>
