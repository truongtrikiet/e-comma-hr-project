<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\EmployeeStatus;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;

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
            $data['password'] = Hash::make($data['password']) ?? Hash::make('Abcd@123');

            $user = $this->userRepository->create($data);

            $data['employment_status'] = EmployeeStatus::ACTIVE->value;

            $user->userProfile()->create($data);

            if (isset($data['user_avatar']) && $data['user_avatar']) {
                $this->updateAvatar($user, $data['user_avatar']);
            }

            if (isset($data['subject_id']) && $data['subject_id']) {
                $user->subjects()->sync(Arr::wrap($data['subject_id']));
            }

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? null);

            if (isset($data['roles']) && checkPermission(Acl::PERMISSION_ASSIGNEE)) {
                $rolesInput = $data['roles'];
                if (is_string($rolesInput)) {
                    $rolesInput = $rolesInput === '' ? [] : array_filter(array_map('trim', explode(',', $rolesInput)));
                } elseif (!is_array($rolesInput)) {
                    $rolesInput = (array) $rolesInput;
                }

                $user->syncRoles(array_map(fn($role) => (int) $role, (array) $rolesInput));
            }

            \Log::info('User created: ' . json_encode($user->toArray()));

            $user->save();

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

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            if (isset($data['last_name']) && isset($data['first_name'])) {
                $data['name'] = $data['last_name'] . ' ' . $data['first_name'];
            }

            if (isset($data['subject_id']) && $data['subject_id']) {
                $user->subjects()->sync(Arr::wrap($data['subject_id']));
            }

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? $user->school_id);

            $user = $this->userRepository->update($user, $data);


            if (isset($data['user_avatar']) && $data['user_avatar']) {
                $this->updateAvatar($user, $data['user_avatar']);
            }

            if (isset($data['roles']) && checkPermission(Acl::PERMISSION_ASSIGNEE)) {
                $rolesInput = $data['roles'];
                if (is_string($rolesInput)) {
                    $rolesInput = $rolesInput === '' ? [] : array_filter(array_map('trim', explode(',', $rolesInput)));
                } elseif (!is_array($rolesInput)) {
                    $rolesInput = (array) $rolesInput;
                }

                $user->syncRoles(array_map(fn($role) => (int) $role, (array) $rolesInput));
            }

            if ($user->userProfile) {
                $user->userProfile->update($data);
            }

            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function updateAvatar(User $user, $avatarData)
    {
        $user->clearMediaCollection(User::USER_AVATAR_COLLECTION);
        $file = is_array($avatarData) ? $avatarData : json_decode($avatarData, true);

        $data = $file['data'] ?? null;
        $name = $file['name'] ?? null;

        if ($data && $name) {
            if (preg_match('/^data:.*;base64,/', $data)) {
                $data = substr($data, strpos($data, ',') + 1);
            }

            $user->addMediaFromBase64($data)
                ->usingFileName($name)
                ->toMediaCollection(User::USER_AVATAR_COLLECTION);
        }

        return $user;
    }
}
