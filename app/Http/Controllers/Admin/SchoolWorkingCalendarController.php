<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\DayEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolWorkingCalendar\StoreSchoolWorkingCalendarRequest;
use App\Http\Requests\SchoolWorkingCalendar\UpdateSchoolWorkingCalendarRequest;
use App\Http\Resources\School\SchoolWorkingCalendarResource;
use App\Models\SchoolWorkingCalendar;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Repositories\SchoolWorkingCalendar\SchoolWorkingCalendarRepositoryInterface;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolWorkingCalendarController extends Controller
{
    public function __construct(
        protected SchoolWorkingCalendarRepositoryInterface $schoolWorkingCalendarRepository,
        protected SchoolRepositoryInterface $schoolRepository,
        protected SchoolService $schoolService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_ADD)->only('create', 'store');
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_EDIT)->only('edit', 'update');
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_DELETE)->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolWorkingCalendars = $this->schoolWorkingCalendarRepository->serverPaginationFiltering($request->all());

            return SchoolWorkingCalendarResource::collection($schoolWorkingCalendars);
        }
        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.school_working_calendar.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $daysOfWeek = DayEnum::options();
        $statuses = ActiveStatus::options();

        return view('admin.school_working_calendar.create', compact('schools', 'daysOfWeek', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolWorkingCalendarRequest $request)
    {
        $this->schoolService->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.school-working-calendar.store')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school-working-calendar.store'));

        return to_route('admin.school-working-calendar.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolWorkingCalendar $schoolWorkingCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolWorkingCalendar $schoolWorkingCalendar)
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $daysOfWeek = DayEnum::options();
        $statuses = ActiveStatus::options();

        return view('admin.school_working_calendar.edit', compact(
            'schoolWorkingCalendar',
            'schools',
            'daysOfWeek',
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolWorkingCalendarRequest $request, SchoolWorkingCalendar $schoolWorkingCalendar)
    {
        $this->schoolWorkingCalendarRepository->update($schoolWorkingCalendar, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.school-working-calendar.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school-working-calendar.update'));

        return to_route('admin.school-working-calendar.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolWorkingCalendar $schoolWorkingCalendar)
    {
        $this->schoolWorkingCalendarRepository->destroy($schoolWorkingCalendar) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.school-working-calendar.delete')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school-working-calendar.delete'));

        return to_route('admin.school-working-calendar.index');
    }
}
