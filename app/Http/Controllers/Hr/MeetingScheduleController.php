<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\MeetingTargetType;
use App\Http\Requests\MeetingSchedule\StoreMeetingScheduleRequest;
use App\Http\Requests\MeetingSchedule\UpdateMeetingScheduleRequest;
use App\Http\Resources\MeetingSchedule\MeetingScheduleResource;
use App\Models\MeetingSchedule;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\MeetingSchedule\MeetingScheduleRepositoryInterface;
use App\Repositories\MeetingScheduleTarget\MeetingScheduleTargetRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\MeetingScheduleService;

class MeetingScheduleController extends Controller
{
    public function __construct(
        protected MeetingScheduleRepositoryInterface $meetingScheduleRepository,
        protected MeetingScheduleService $meetingScheduleService,
        protected MeetingScheduleTargetRepositoryInterface $meetingScheduleTargetRepository,
        protected UserRepositoryInterface $userRepository,
        protected DepartmentRepositoryInterface $departmentRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_MEETING_SCHEDULE_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_MEETING_SCHEDULE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_MEETING_SCHEDULE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_MEETING_SCHEDULE_DELETE)->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $meetingSchedules = $this->meetingScheduleRepository->serverPaginationFiltering($request->all());

            return MeetingScheduleResource::collection($meetingSchedules);
        }

        return view('hr.meeting_schedule.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $meetingTargetTypes = MeetingTargetType::options();
        $users = $this->userRepository->getUsersBySchoolId(auth()->user()->school_id);
        $departments = $this->departmentRepository->getDepartmentsBySchoolId(auth()->user()->school_id);

        return view('hr.meeting_schedule.create', compact(
            'meetingTargetTypes',
            'users',
            'departments'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeetingScheduleRequest $request)
    {
        $this->meetingScheduleService->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.meeting-schedule.create'))
            : session()->flash(NOTIFICATION_ERROR, __('error.meeting-schedule.create'));

        return to_route('hr.meeting-schedule.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingSchedule $meetingSchedule)
    {
        return view('hr.meeting_schedule.show', compact('meetingSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingSchedule $meetingSchedule)
    {
        $meetingTargetTypes = MeetingTargetType::options();
        $users = $this->userRepository->getUsersBySchoolId(auth()->user()->school_id);
        $departments = $this->departmentRepository->getDepartmentsBySchoolId(auth()->user()->school_id);

        return view('hr.meeting_schedule.edit', compact(
            'meetingSchedule',
            'meetingTargetTypes',
            'users',
            'departments'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeetingScheduleRequest $request, MeetingSchedule $meetingSchedule)
    {
        $this->meetingScheduleService->update($meetingSchedule, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.meeting-schedule.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.meeting-schedule.update'));

        return to_route('hr.meeting-schedule.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeetingSchedule $meetingSchedule)
    {
        $this->meetingScheduleRepository->destroy($meetingSchedule) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.meeting-schedule.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.meeting-schedule.delete'));

        return to_route('hr.meeting-schedule.index');
    }
}
