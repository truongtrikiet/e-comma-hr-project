<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Acl\Acl;
use App\Http\Resources\School\SchoolResource;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolRepositoryInterface $schoolRepository,
    ) {
        $this->middleware('permission: ' . Acl::PERMISSION_SCHOOL_LIST)->only('index');
        $this->middleware('permission: ' . Acl::PERMISSION_SCHOOL_ADD)->only(['create', 'store']);
        $this->middleware('permission: ' . Acl::PERMISSION_SCHOOL_EDIT)->only(['edit', 'update']);
        $this->middleware('permission: ' . Acl::PERMISSION_SCHOOL_DELETE)->only('destroy');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
