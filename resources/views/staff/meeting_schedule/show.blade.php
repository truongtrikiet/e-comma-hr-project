<x-staff.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.meeting_schedule_management.view_meeting_schedule') }}
    </x-slot:pageTitle>

    <x-slot:headerFiles>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </x-slot:headerFiles>

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

                        </div>
                    </div>
                </div>

                </div>
                <div class="card-footer">
                    <x-buttons.button-link :label="__('general.common.back')" :url="url()->previous()" />
                </div>
            </div>
        </div>
    </div>
</x-staff.base-layout>
