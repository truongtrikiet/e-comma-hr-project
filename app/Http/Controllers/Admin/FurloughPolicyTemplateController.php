<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FurloughPolicyTemplate\FurloughPolicyTemplateResource;
use App\Repositories\FurloughPolicyTemplate\FurloughPolicyTemplateRepositoryInterface;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use App\Enum\MonthEnum;
use App\Http\Requests\FurloughPolicyTemplate\StoreFurloughPolicyTemplate;
use App\Http\Requests\FurloughPolicyTemplate\UpdateFurloughPolicyTemplate;
use App\Models\FurloughPolicyTemplate;

class FurloughPolicyTemplateController extends Controller
{
    public function __construct(
        protected FurloughPolicyTemplateRepositoryInterface $furloughPolicyTemplateRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_LIST)->only(['index', 'show']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_DELETE)->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $furloughPolicyTemplates = $this->furloughPolicyTemplateRepository->serverPaginationFiltering($request->all());

            return FurloughPolicyTemplateResource::collection($furloughPolicyTemplates);
        }
        return view('admin.furlough_policy_template.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $isPaid = IsPaid::options();
        $statuses = ActiveStatus::options();

        return view('admin.furlough_policy_template.create', compact('isPaid', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFurloughPolicyTemplate $request)
    {
        $this->furloughPolicyTemplateRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy-template.store')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy-template.store'));

        return to_route('admin.furlough-policy-template.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(FurloughPolicyTemplate $furloughPolicyTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FurloughPolicyTemplate $furloughPolicyTemplate)
    {
        $isPaid = IsPaid::options();
        $statuses = ActiveStatus::options();

        return view('admin.furlough_policy_template.edit', compact(
            'furloughPolicyTemplate',
            'isPaid',
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFurloughPolicyTemplate $request, FurloughPolicyTemplate $furloughPolicyTemplate)
    {
        $this->furloughPolicyTemplateRepository->update($furloughPolicyTemplate, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy-template.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy-template.update'));

        return to_route('admin.furlough-policy-template.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FurloughPolicyTemplate $furloughPolicyTemplate)
    {
        $this->furloughPolicyTemplateRepository->destroy($furloughPolicyTemplate) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough-policy-template.destroy')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough-policy-template.destroy'));

        return to_route('admin.furlough-policy-template.index');
    }
}
