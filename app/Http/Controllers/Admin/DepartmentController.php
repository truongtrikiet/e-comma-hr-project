<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Enum\DepartmentType;
use App\Enum\SettingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\Department\DepartmentResource;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(
        protected DepartmentRepositoryInterface $departmentRepository,
        protected SchoolRepositoryInterface $schoolRepository,
        protected DepartmentService $departmentService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_DEPARTMENT_LIST)->only(['index', 'show']);
        $this->middleware('permission:' . Acl::PERMISSION_DEPARTMENT_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_DEPARTMENT_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_DEPARTMENT_DELETE)->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = $this->departmentRepository->serverPaginationFiltering($request->all());

            return DepartmentResource::collection($departments);
        }

        return view('admin.department.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = $this->departmentRepository->all();
        $types = DepartmentType::options();
        $statuses = SettingStatus::options();
        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.department.create', compact(
            'departments', 
            'types', 
            'statuses',
            'schools'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $this->departmentService->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.department.create'))
            : session()->flash(NOTIFICATION_ERROR, __('error.department.create'));

        return to_route('admin.department.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $departments = $this->departmentRepository->all();
        $types = DepartmentType::options();
        $statuses = SettingStatus::options();
        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.department.edit', compact(
            'department', 
            'departments', 
            'types', 
            'statuses',
            'schools'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->departmentService->update($department, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.department.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.department.update'));

        return to_route('admin.department.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->departmentRepository->destroy($department) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.department.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.department.delete'));

        return to_route('admin.department.index');
    }
}
