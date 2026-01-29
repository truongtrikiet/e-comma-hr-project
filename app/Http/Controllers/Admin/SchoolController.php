<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Acl\Acl;
use App\Enum\SslStatus;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Http\Resources\School\SchoolResource;
use App\Models\School;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolRepositoryInterface $schoolRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_SCHOOL_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schools = $this->schoolRepository->serverPaginationFilteringForAdmin($request->all());

            return SchoolResource::collection($schools);
        }

        return view('admin.school.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = SslStatus::options();

        return view('admin.school.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolRequest $request)
    {
        $this->schoolRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.school.create')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school.create'));

        return to_route('admin.school.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $statuses = SslStatus::options();

        return view('admin.school.edit', compact('school', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolRequest $request, School $school)
    {
        $this->schoolRepository->update($school, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.school.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school.update'));

        return to_route('admin.school.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $this->schoolRepository->destroy($school) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.school.delete')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.school.delete'));
    }
}
