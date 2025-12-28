<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
        $this->middleware('permission: ' . Acl::PERMISSION_USER_LIST)->only('index');
        $this->middleware('permission: ' . Acl::PERMISSION_USER_ADD)->only(['create', 'store']);
        $this->middleware('permission: ' . Acl::PERMISSION_USER_EDIT)->only(['edit', 'update']);
        $this->middleware('permission: ' . Acl::PERMISSION_USER_DELETE)->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->userRepository->serverPaginationFilteringForAdmin($request->all());

            return UserResource::collection($users);
        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
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
