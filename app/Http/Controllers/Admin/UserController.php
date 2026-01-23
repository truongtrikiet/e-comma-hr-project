<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Enum\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService $userService,
        protected RoleRepositoryInterface $roleRepository,
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
            $users = $this->userRepository->serverPaginationFiltering($request->all());

            return UserResource::collection($users);
        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleRepository->all();
        $statuses = UserStatus::options(true);

        return view('admin.user.create', compact('roles', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.user.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.user.store'));

        return to_route('admin.user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = $this->roleRepository->all();
        $statuses = UserStatus::options(true);

        return view('admin.user.edit', compact('user', 'roles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.user.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.user.update'));

        return to_route('admin.user.index');
    }

    /**
     * Update the profile of the logged-in user.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->userRepository->update(Auth::user(), $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.user.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.user.update'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($this->userRepository->destroy($user))
            return response()->json([
                'message' => __('success.delete'),
            ], Response::HTTP_OK);
        return response()->json([
            'message' => __('error.delete'),
        ], Response::HTTP_BAD_REQUEST);
    }

    public function updateAvatar(Request $request)
    {
        $this->userService->updateAvatar(Auth::user(), $request->file('avatar')) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.user.update_avatar'))
            : session()->flash(NOTIFICATION_ERROR, __('error.user.update_avatar'));

        return redirect()->back();
    }
}
