<?php

namespace App\Http\Controllers\Hr;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Http\Resources\FurloughPolicy\FurloughPolicyResource;
use App\Repositories\FurloughPolicy\FurloughPolicyRepositoryInterface;
use Illuminate\Http\Request;
use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use App\Enum\MonthEnum;
use App\Enum\ResetTypeEnum;
use App\Http\Requests\FurloughPolicy\StoreFurloughPolicy;
use App\Http\Requests\FurloughPolicy\UpdateFurloughPolicy;
use App\Models\FurloughPolicy;
use App\Repositories\EmployeeType\EmployeeTypeRepositoryInterface;
use App\Repositories\FurloughPolicyTemplate\FurloughPolicyTemplateRepositoryInterface;
use App\Repositories\FurloughType\FurloughTypeRepositoryInterface;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Services\FurloughPolicyService;

class FurloughPolicyController extends Controller
{
    public function __construct(
        protected FurloughPolicyRepositoryInterface $furloughPolicyRepository,
        protected FurloughPolicyTemplateRepositoryInterface $furloughPolicyTemplateRepository,
        protected EmployeeTypeRepositoryInterface $employeeTypeRepository,
        protected FurloughTypeRepositoryInterface $furloughTypeRepository,
        protected SchoolRepositoryInterface $schoolRepository,
        protected FurloughPolicyService $furloughPolicyService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_LIST)->only(['index', 'show']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_DELETE)->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $furloughPolicies = $this->furloughPolicyRepository->serverPaginationFiltering($request->all());

            return FurloughPolicyResource::collection($furloughPolicies);
        }
        return view('hr.furlough_policy.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $isPaid = IsPaid::options();
        $statuses = ActiveStatus::options();
        $furloughPolicyTemplates = $this->furloughPolicyTemplateRepository->getFurloughPolicyTemplate();
        $employeeTypes = $this->employeeTypeRepository->getActiveEmployeeTypes();
        $furloughTypes = $this->furloughTypeRepository->getActiveFurloughTypes();
        $resetTypes = ResetTypeEnum::options();
        $schools = $this->schoolRepository->getSchoolActive();
        $months = MonthEnum::options();

        return view('hr.furlough_policy.create', compact(
            'isPaid', 
            'statuses',
            'furloughPolicyTemplates',
            'employeeTypes',
            'furloughTypes',
            'resetTypes',
            'schools',
            'months'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFurloughPolicy $request)
    {
        $this->furloughPolicyService->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy.store')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy.store'));

        return to_route('hr.furlough-policies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(FurloughPolicy $furloughPolicy)
    {
        return view('hr.furlough_policy.show', compact('furloughPolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FurloughPolicy $furloughPolicy)
    {
        $isPaid = IsPaid::options();
        $statuses = ActiveStatus::options();
        $furloughPolicyTemplates = $this->furloughPolicyTemplateRepository->getFurloughPolicyTemplate();
        $employeeTypes = $this->employeeTypeRepository->getActiveEmployeeTypes();
        $furloughTypes = $this->furloughTypeRepository->getActiveFurloughTypes();
        $resetTypes = ResetTypeEnum::options();
        $schools = $this->schoolRepository->getSchoolActive();
        $months = MonthEnum::options();

        return view('hr.furlough_policy.edit', compact(
            'furloughPolicy',
            'isPaid',
            'statuses',
            'furloughPolicyTemplates',
            'employeeTypes',
            'furloughTypes',
            'resetTypes',
            'schools',
            'months'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFurloughPolicy $request, FurloughPolicy $furloughPolicy)
    {
        $this->furloughPolicyService->update($furloughPolicy, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy.update'));

        return to_route('hr.furlough-policies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FurloughPolicy $furloughPolicy)
    {
        $this->furloughPolicyRepository->destroy($furloughPolicy) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy.destroy')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy.destroy'));

        return to_route('hr.furlough-policies.index');
    }
}
