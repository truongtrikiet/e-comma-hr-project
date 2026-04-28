<x-base-layout :scrollspy="false">
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
            __('general.menu.meeting_schedule_management.manage_meeting_schedule') => route('admin.meeting-schedule.index'),
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

                            <div class="mb-2">
                                <label><h6>{{ __('general.common.meeting_target') }}:</h6></label>
                                @if($meetingSchedule->targets && $meetingSchedule->targets->isNotEmpty())
                                    <ul>
                                        @foreach($meetingSchedule->targets as $t)
                                            @php
                                                $type = $t->target_type?->value ?? $t->target_type;
                                            @endphp
                                            <li>
                                                <strong>{{ \App\Enum\MeetingTargetType::getNameByValue($type) }}:</strong>
                                                @switch($type)
                                                    @case(\App\Enum\MeetingTargetType::USER->value)
                                                        @php $u = \App\Models\User::find($t->target_id); @endphp
                                                        {{ $u?->name ?? ('#' . $t->target_id) }}
                                                        @break
                                                    @case(\App\Enum\MeetingTargetType::DEPARTMENT->value)
                                                        @php $d = \App\Models\Department::find($t->target_id); @endphp
                                                        {{ $d?->name ?? ('#' . $t->target_id) }}
                                                        @break
                                                    @case(\App\Enum\MeetingTargetType::SCHOOL->value)
                                                        @php $s = \App\Models\School::find($t->target_id); @endphp
                                                        {{ $s?->name ?? ('#' . $t->target_id) }}
                                                        @break
                                                    @default
                                                        {{ $t->target_id }}
                                                @endswitch
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
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
                        <x-buttons.button-link :label="__('general.common.edit')" :url="route('admin.meeting-schedule.edit', $meetingSchedule->id)" />
                    @endcan
                    <x-buttons.button-link :label="__('general.common.back')" :url="route('admin.meeting-schedule.index')" />
                </div>
            </div>
        </div>
    </div>

</x-base-layout>
