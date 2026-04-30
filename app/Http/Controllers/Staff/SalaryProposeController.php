<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SalaryPropose\StoreSalaryProposeRequest;
use App\Http\Requests\SalaryPropose\UpdateSalaryProposeRequest;
use App\Http\Resources\Salary\SalaryProposeResource;
use App\Models\SalaryPropose;
use App\Repositories\SalaryPropose\SalaryProposeRepositoryInterface;
use App\Acl\Acl;

class SalaryProposeController extends Controller
{
    public function __construct(
        protected SalaryProposeRepositoryInterface $salaryProposeRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $salaryProposes = $this->salaryProposeRepository->serverPaginationFilteringByStaff($request->all());

            return SalaryProposeResource::collection($salaryProposes);
        }

        return view('staff.salary_propose.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.salary_propose.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryProposeRequest $request)
    {
        $this->salaryProposeRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.store')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.store'));

        return to_route('staff.salary-propose.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryPropose $salaryPropose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryPropose $salaryPropose)
    {
        return view('staff.salary_propose.edit', compact('salaryPropose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryProposeRequest $request, SalaryPropose $salaryPropose)
    {
        $this->salaryProposeRepository->update($salaryPropose, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.update'));

        return to_route('staff.salary-propose.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryPropose $salaryPropose)
    {
        $this->salaryProposeRepository->destroy($salaryPropose) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.delete')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.delete'));

        return to_route('staff.salary-propose.index');
    }
}
