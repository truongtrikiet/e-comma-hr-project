<?php

namespace App\Http\Controllers\Hr;

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
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_VIEW)->only('show');
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_PROPOSE_APPROVE)->only('approved');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $salaryProposes = $this->salaryProposeRepository->serverPaginationFiltering($request->all());

            return SalaryProposeResource::collection($salaryProposes);
        }

        return view('hr.salary_propose.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hr.salary_propose.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryProposeRequest $request)
    {
        $this->salaryProposeRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.create')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.create'));

        return to_route('hr.salary-propose.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryPropose $salaryPropose)
    {
        return view('hr.salary_propose.show', compact('salaryPropose'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryPropose $salaryPropose)
    {
        return view('hr.salary_propose.edit', compact('salaryPropose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryProposeRequest $request, SalaryPropose $salaryPropose)
    {
        $this->salaryProposeRepository->update($salaryPropose, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.update'));

        return to_route('hr.salary-propose.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryPropose $salaryPropose)
    {
        $this->salaryProposeRepository->destroy($salaryPropose) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.delete')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.delete'));

        return to_route('hr.salary-propose.index');
    }

    /**
     * Approved salary propose request.
     */
    public function approved(SalaryPropose $salaryPropose, Request $request)
    {
        $this->salaryProposeRepository->approved($salaryPropose, $request->all()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary-propose.approved')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.salary-propose.approved'));

        return to_route('hr.salary-propose.show', $salaryPropose);
    }
}
