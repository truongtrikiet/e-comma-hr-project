<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Admin Dashboard') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    </x-slot:headerFiles>

    <div>
        <h1>Dashboard</h1>
    </div>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.dashboard') => '',
        ]"
    />

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-2">{{ __('general.menu.furlough_balance_management.title') }}</h5>
                </div>
                <div class="card-body p-3">
                    @php
                        $balances = \App\Models\FurloughBalance::where('user_id', auth()->id())
                            ->with('furloughType')
                            ->get();
                    @endphp

                    @if($balances->isEmpty())
                        <div class="text-muted large text-center">No furlough balances found.</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('general.common.type') }}</th>
                                    <th class="text-center">{{ __('general.dashboard.total_leave_days') }}</th>
                                    <th class="text-center">{{ __('general.dashboard.used_leave_days') }}</th>
                                    <th class="text-center">{{ __('general.dashboard.remaining_leave_days') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($balances as $balance)
                                    <tr>
                                        <td>{{ $balance->furloughType?->localeName ?? __('-') }}</td>
                                        <td class="text-center">{{ $balance->total_days }}</td>
                                        <td class="text-center">{{ $balance->used_days }}</td>
                                        <td class="text-center">{{ $balance->remaining_days }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2">{{ __('general.common.meeting') }}</h5>
                </div>
                @php
                    $upcomingMeetings = \App\Models\MeetingSchedule::where('school_id', auth()->user()->school_id)
                        ->where('end_time', '>=', now())
                        ->orderBy('start_time')
                        ->limit(2)
                        ->get();
                @endphp

                @if($upcomingMeetings->isEmpty())
                    <div class="card-body p-4 text-center text-muted">
                        <div class="large">No upcoming meetings.</div>
                    </div>
                @else
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0">
                            @foreach($upcomingMeetings as $m)
                                @php
                                    $statusValue = $m->status?->value ?? $m->status;
                                    $statusName = \App\Enum\MeetingScheduleStatus::getNameByValue($statusValue) ?? '-';
                                    $badge = $m->status?->getBadge() ?? 'secondary';
                                @endphp
                                <li class="mb-2 py-2 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1 me-3" style="font-size:1rem;font-weight:600;">{{ $m->title }}</div>
                                        <div class="me-3 text-muted" style="font-size:0.9rem; width:140px; text-align:right;">{{ customDateFormat($m->start_time) }}</div>
                                        <div style="width:120px; text-align:right;">
                                            <span class="badge badge-{{ $badge }}">{{ $statusName }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right column: square month calendar -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ __('general.common.calendar') }}</h5>
                    @php
                        $today = \Carbon\Carbon::today();
                        $year = $today->year;
                        $month = $today->month;
                        $firstOfMonth = \Carbon\Carbon::create($year, $month, 1);
                        $startDay = $firstOfMonth->dayOfWeek;
                        $daysInMonth = $firstOfMonth->daysInMonth;

                        $monthStart = $firstOfMonth->copy()->startOfMonth();
                        $monthEnd = $firstOfMonth->copy()->endOfMonth();

                        $holidays = \App\Models\HolidaySchedule::whereDate('start_date', '<=', $monthEnd)
                            ->whereDate('end_date', '>=', $monthStart)
                            ->get();

                        $holidayDates = [];
                        foreach ($holidays as $h) {
                            $rangeStart = $h->start_date->copy()->max($monthStart);
                            $rangeEnd = $h->end_date->copy()->min($monthEnd);
                            for ($d = $rangeStart->copy(); $d->lte($rangeEnd); $d->addDay()) {
                                $holidayDates[$d->format('Y-m-d')] = true;
                            }
                        }
                    @endphp
                    <small class="text-muted">{{ $firstOfMonth->translatedFormat('F Y') }}</small>
                </div>
                <div class="card-body p-3">
                    <!-- Square container -->
                    <div class="w-100" style="aspect-ratio:1; overflow:auto;">
                        <table class="table table-sm mb-0" style="table-layout:fixed; height:100%;">
                            <thead>
                                <tr>
                                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                                        <th class="text-center small">{{ $d }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $day = 1;
                                @endphp
                                @for($week = 0; $week < 6; $week++)
                                    <tr style="height: calc(100% / 6);">
                                        @for($dow = 0; $dow < 7; $dow++)
                                            @if($week == 0 && $dow < $startDay)
                                                <td></td>
                                            @elseif($day > $daysInMonth)
                                                <td></td>
                                            @else
                                                @php
                                                    $cellDate = \Carbon\Carbon::create($year, $month, $day);
                                                    $isToday = $cellDate->isSameDay($today);
                                                @endphp
                                                @php
                                                    $cellKey = $cellDate->format('Y-m-d');
                                                    $isHoliday = isset($holidayDates[$cellKey]);
                                                @endphp
                                                <td class="text-center align-top small" style="vertical-align:top;">
                                                    <div @class([
                                                        'p-1',
                                                        'rounded' => !$isHoliday && $isToday,
                                                        'bg-primary text-white' => !$isHoliday && $isToday,
                                                        'bg-danger text-white' => $isHoliday,
                                                    ])>
                                                        {{ $day }}
                                                    </div>
                                                    <!-- future: event markers can be placed here -->
                                                </td>
                                                @php $day++; @endphp
                                            @endif
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('general.common.calendar') }}</h5>
                //
            </div>
            <div class="card-body p-3">
                <div class="w-100" style="aspect-ratio:1; overflow:auto;">
                    <table class="table table-sm mb-0" style="table-layout:fixed; height:100%;">
                        <thead>
                            //
                        </thead>
                        <tbody>
                            //
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->

</x-base-layout>