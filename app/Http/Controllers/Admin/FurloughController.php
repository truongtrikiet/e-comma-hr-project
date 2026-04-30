<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Furlough\FurloughRepositoryInterface;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\FurloughStatus;
use App\Enum\UseBalanceFurloughEnum;
use App\Http\Requests\Furlough\ApprovedFurloughRequest;
use App\Http\Requests\Furlough\StoreFurloughRequest;
use App\Http\Requests\Furlough\UpdateFurloughRequest;
use App\Http\Resources\Furlough\FurloughResource;
use App\Models\Furlough;
use App\Repositories\FurloughType\FurloughTypeRepositoryInterface;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Services\FurloughService;

class FurloughController extends Controller
{
    public function __construct(
        protected FurloughRepositoryInterface $furloughRepository,
        protected FurloughTypeRepositoryInterface $furloughTypeRepository,
        protected SchoolRepositoryInterface $schoolRepository,
        protected FurloughService $furloughService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_LIST)->only(['index']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_DELETE)->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $furloughs = $this->furloughRepository->serverPaginationFiltering($request->all());

            return FurloughResource::collection($furloughs);
        }
        $schools = $this->schoolRepository->getSchoolActive();
        $furloughTypes = $this->furloughTypeRepository->getActiveFurloughTypes();

        return view('admin.furlough.index', compact('schools', 'furloughTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $furloughTypes = $this->furloughTypeRepository->getActiveFurloughTypes();
        $useBalance = UseBalanceFurloughEnum::options();

        return view('admin.furlough.create', compact('furloughTypes', 'useBalance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFurloughRequest $request)
    {
        $this->furloughService->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough.store'));

        return to_route('admin.furlough.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Furlough $furlough)
    {
        $furloughStatuses = FurloughStatus::options();

        return view('admin.furlough.show', compact('furlough', 'furloughStatuses'));
    }

    /**
     * Approved furlough request.
     */
    public function approved(ApprovedFurloughRequest $request, Furlough $furlough)
    {
        $this->furloughService->approved($furlough, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough.apply'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough.apply'));

        return to_route('admin.furlough.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Furlough $furlough)
    {
        $furloughTypes = $this->furloughTypeRepository->getActiveFurloughTypes();
        $useBalance = UseBalanceFurloughEnum::options();

        return view('admin.furlough.edit', compact('furlough', 'furloughTypes', 'useBalance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFurloughRequest $request, Furlough $furlough)
    {
        $this->furloughService->update($furlough, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough.update'));

        return to_route('admin.furlough.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Furlough $furlough)
    {
        $this->furloughRepository->destroy($furlough) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough.delete'));

        return to_route('admin.furlough.index');
    }
}
