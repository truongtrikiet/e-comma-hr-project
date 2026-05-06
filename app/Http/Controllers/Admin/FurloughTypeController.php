<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\FurloughType\FurloughTypeRepositoryInterface;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Http\Requests\FurloughType\StoreFurloughTypeRequest;
use App\Http\Requests\FurloughType\UpdateFurloughTypeRequest;
use App\Http\Resources\FurloughType\FurloughTypeResource;
use App\Models\FurloughType;

class FurloughTypeController extends Controller
{
    public function __construct(
        protected FurloughTypeRepositoryInterface $furloughTypeRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_TYPE_LIST)->only(['index', 'show']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_TYPE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_TYPE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_FURLOUGH_TYPE_DELETE)->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $furloughTypes = $this->furloughTypeRepository->serverPaginationFiltering($request->all());

            return FurloughTypeResource::collection($furloughTypes);
        }

        return view('admin.furlough_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ActiveStatus::options();

        return view('admin.furlough_type.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFurloughTypeRequest $request)
    {
        $this->furloughTypeRepository->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough_type.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough_type.store'));

        return to_route('admin.furlough-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(FurloughType $furloughType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FurloughType $furloughType)
    {
        $statuses = ActiveStatus::options();

        return view('admin.furlough_type.edit', compact('furloughType', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFurloughTypeRequest $request, FurloughType $furloughType)
    {
        $this->furloughTypeRepository->update($furloughType, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough_type.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough_type.update'));

        return to_route('admin.furlough-type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FurloughType $furloughType)
    {
        $this->furloughTypeRepository->destroy($furloughType) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.furlough_type.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.furlough_type.delete'));

        return to_route('admin.furlough-type.index');
    }
}
