<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Http\Requests\EmployeeType\StoreEmployeeTypeRequest;
use App\Http\Requests\EmployeeType\UpdateEmployeeTypeRequest;
use App\Http\Resources\EmployeeType\EmployeeTypeResource;
use App\Models\EmployeeType;
use App\Repositories\EmployeeType\EmployeeTypeRepositoryInterface;

class EmployeeTypeController extends Controller
{
    public function __construct(
        protected EmployeeTypeRepositoryInterface $employeeTypeRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_EMPLOYEE_TYPE_LIST)->only(['index', 'show']);
        $this->middleware('permission:' . Acl::PERMISSION_EMPLOYEE_TYPE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_EMPLOYEE_TYPE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_EMPLOYEE_TYPE_DELETE)->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employeeTypes = $this->employeeTypeRepository->serverPaginationFiltering($request->all());

            return EmployeeTypeResource::collection($employeeTypes);
        }

        return view('admin.employee_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ActiveStatus::options();

        return view('admin.employee_type.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeTypeRequest $request)
    {
        $this->employeeTypeRepository->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.employee_type.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.employee_type.store'));

        return to_route('admin.employee-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeType $employeeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeType $employeeType)
    {
        $statuses = ActiveStatus::options();

        return view('admin.employee_type.edit', compact('employeeType', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeTypeRequest $request, EmployeeType $employeeType)
    {
        $this->employeeTypeRepository->update($employeeType, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.employee_type.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.employee_type.update'));

        return to_route('admin.employee-type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeType $employeeType)
    {
        $this->employeeTypeRepository->destroy($employeeType) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.employee_type.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.employee_type.delete'));

        return to_route('admin.employee-type.index');
    }
}
