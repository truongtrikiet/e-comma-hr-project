<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\HolidaySchedule\HolidayScheduleRepositoryInterface;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Http\Requests\HolidaySchedule\StoreHolidayScheduleRequest;
use App\Http\Requests\HolidaySchedule\UpdateHolidayScheduleRequest;
use App\Models\HolidaySchedule;

class HolidayScheduleController extends Controller
{
    public function __construct(
        protected HolidayScheduleRepositoryInterface $holidayScheduleRepostory,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_HOLIDAY_SCHEDULE_LIST)->only(['index', 'list']);
        $this->middleware('permission:' . Acl::PERMISSION_HOLIDAY_SCHEDULE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_HOLIDAY_SCHEDULE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_HOLIDAY_SCHEDULE_DELETE)->only(['destroy']);
        $this->middleware('permission:' . Acl::PERMISSION_HOLIDAY_SCHEDULE_SHOW)->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $holidays = $this->holidayScheduleRepostory->all();

        return view('admin.holiday_schedule.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayScheduleRequest $request)
    {
        $created = $this->holidayScheduleRepostory->create($request->validated());

        $created
            ? session()->flash(NOTIFICATION_SUCCESS, __('success.holiday-schedule.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.holiday-schedule.store'));

        if ($request->wantsJson()) {
            return response()->json(['success' => (bool) $created]);
        }

        return to_route('admin.holiday-schedule.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(HolidaySchedule $holidaySchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidaySchedule $holidaySchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayScheduleRequest $request, HolidaySchedule $holidaySchedule)
    {
        $updated = $this->holidayScheduleRepostory->update($holidaySchedule, $request->validated());

        $updated
            ? session()->flash(NOTIFICATION_SUCCESS, __('success.holiday-schedule.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.holiday-schedule.update'));

        if ($request->wantsJson()) {
            return response()->json(['success' => (bool) $updated]);
        }

        return to_route('admin.holiday-schedule.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidaySchedule $holidaySchedule)
    {
        $deleted = $this->holidayScheduleRepostory->destroy($holidaySchedule);

        $deleted
            ? session()->flash(NOTIFICATION_SUCCESS, __('success.holiday-schedule.destroy'))
            : session()->flash(NOTIFICATION_ERROR, __('error.holiday-schedule.destroy'));

        if (request()->wantsJson()) {
            return response()->json(['success' => (bool) $deleted]);
        }

        return to_route('admin.holiday-schedule.index');
    }

    /**
     * List all holiday schedules.
     */
    public function list()
    {
        $holidays = $this->holidayScheduleRepostory->all();
        $events = [];

        foreach ($holidays as $holiday) {
            $start = $holiday->start_date ? $holiday->start_date->format('Y-m-d') : null;
            $end = $holiday->end_date ? date('Y-m-d', strtotime($holiday->end_date . ' +1 day')) : $start;

            $events[] = [
                'id' => $holiday->id,
                'title' => $holiday->getTranslation('name', app()->getLocale()),
                'start' => $start,
                'end' => $end,
                'allDay' => true,
                'backgroundColor' => '#e74c3c',
                'borderColor' => '#c0392b',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'holiday' => [
                        'id' => $holiday->id,
                        'names' => $holiday->getTranslations('name'),
                        'start_date' => $holiday->start_date ? $holiday->start_date->format('Y-m-d') : null,
                        'end_date' => $holiday->end_date ? $holiday->end_date->format('Y-m-d') : null,
                        'status' => $holiday->status,
                        'total_days' => $holiday->total_days,
                    ]
                ]
            ];
        }

        return response()->json($events);
    }
}
