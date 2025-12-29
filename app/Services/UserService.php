<?php

namespace App\Services;

use App\Acl\Acl;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Title\TitleRepositoryInterface;
use Spatie\Permission\Models\Role;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
        //
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['name'] = $data['last_name'] . ' ' . $data['first_name'];
            $data['password'] = Hash::make($data['password']);

            $user = $this->userRepository->create($data);

            // $user->address()->create(['address' => $data['address']]);

            $user->userProfile()->create($data);

            // if (isset($data['user_avatar']) && $data['user_avatar']) {
            //     $file = json_decode($data['user_avatar'], true);
            //     $user->addMediaFromBase64($file['data'])
            //         ->usingFileName($file['name'])
            //         ->toMediaCollection(USER_AVATAR_COLLECTION);
            // }

            $user->syncRoles(Arr::map($data['roles'], fn($role) => (int)$role));

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function update(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if (isset($data['last_name']) && isset($data['first_name'])) {
                $data['name'] = $data['last_name'] . ' ' . $data['first_name'];
            }

            $user->update($data);

            if ($user->userProfile) {
                $user->userProfile->update($data);
            }

            // if (isset($data['address']) && $data['address']) {
            //     $user->address()->update(['address' => $data['address']]);
            // }

            // if (isset($data['user_avatar']) && $data['user_avatar']) {
            //     $this->updateAvatar($user, $data['user_avatar']);
            // }

            // if (isset($data['departments']) && $data['departments']) {
            //     $user->departments()->sync($data['departments']);
            // }

            // if (isset($data['titles']) && $data['titles']) {
            //     $this->handleAudit($user, $data);
            // }

            // if (isset($data['employee_types']) && $data['employee_types']) {
            //     $user->employeeType()->sync($data['employee_types']);
            // }

            // if (isset($data['roles']) && checkPermission(Acl::PERMISSION_ASSIGNEE)) {
            //     $user->syncRoles(Arr::map($data['roles'], fn($role) => (int)$role));
            // }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    // public function updateAvatar(User $user, $avatarData)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $user->clearMediaCollection(USER_AVATAR_COLLECTION);
    //         $file = json_decode($avatarData, true);

    //         if (isset($file['data']) && isset($file['name'])) {
    //             $user->addMediaFromBase64($file['data'])
    //                 ->usingFileName($file['name'])
    //                 ->toMediaCollection(USER_AVATAR_COLLECTION);
    //         }

    //         DB::commit();

    //         return $user;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return $e->getMessage();
    //     }
    // }
}
