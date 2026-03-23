<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\EmployeeStatus;
use App\Models\FurloughBalance;
use App\Models\FurloughPolicy;
use App\Models\School;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

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

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? null);

            if (! empty($data['school_id'])) {
                $data['employee_code'] = $this->generateEmployeeCode($data['school_id']);
            } else {
                $data['employee_code'] = $this->generateEmployeeCode(null);
            }

            $user = $this->userRepository->create($data);

            $data['employment_status'] = EmployeeStatus::ACTIVE->value;

            $user->userProfile()->create($data);

            if (isset($data['user_avatar']) && $data['user_avatar']) {
                $this->updateAvatar($user, $data['user_avatar']);
            }

            if (isset($data['subject_id']) && $data['subject_id']) {
                $user->subjects()->sync(Arr::wrap($data['subject_id']));
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

            if (!empty($data['employee_type_id'])) {
                $user->load('userProfile');                
                $this->createInitialFurloughBalances($user);
            }

            Log::info('User created: ' . json_encode($user->toArray()));

            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return null;
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

            $oldEmployeeTypeId = $user->userProfile->employee_type_id ?? null;
            \Log::info('Old Employee Type ID: ' . $oldEmployeeTypeId);

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
                if (empty($user->userProfile->employee_code)) {
                    $schoolId = $data['school_id'] ?? $user->school_id;
                    $data['employee_code'] = $this->generateEmployeeCode($schoolId);
                }

                $user->userProfile->update($data);
            }

            $newEmployeeTypeId = $data['employee_type_id'] ?? ($user->userProfile->employee_type_id ?? null);
            
            if ($newEmployeeTypeId && $oldEmployeeTypeId !== $newEmployeeTypeId) {                
                $user->load('userProfile');
                $this->createInitialFurloughBalances($user);
            }

            Log::info('User updated: ' . json_encode($user->toArray()));

            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return null;
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

    /**
     * Generate a unique employee code based on the school's code_school, current year, and a sequential number.
     */
    protected function generateEmployeeCode($schoolId = null): string
    {
        $yearSuffix = date('y');
        $systemMain = env('SYSTEM_MAIN', 'ecs');

        if ($schoolId) {
            $school = School::find($schoolId);
        } else {
            $school = School::where('sub_domain', $systemMain)->first();
        }

        $prefixCode = $school && $school->code_school ? 
            strtoupper($school->code_school) 
            : strtoupper($systemMain);

        $base = $prefixCode . $yearSuffix;

        $last = UserProfile::where('employee_code', 'like', $base . '%')
            ->orderByDesc('employee_code')
            ->first();

        if (! $last) {
            $next = 1;
        } else {
            $lastCode = $last->employee_code;
            $num = (int) substr($lastCode, -4);
            $next = $num + 1;
        }

        return $base . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create initial furlough balances for a user based on applicable furlough policies.
     */
    protected function createInitialFurloughBalances(User $user): void
    {   
        $employeeTypeId = $user->userProfile->employee_type_id ?? null;
        $schoolId = $user->school_id ?? null;

        Log::info('=== TẠO BALANCE (KHÔNG DÙNG BẢNG TRUNG GIAN) ===', [
            'user_id' => $user->id ?? 'NULL',
            'employee_type_id' => $employeeTypeId ?? 'NULL',
            'school_id' => $schoolId ?? 'NULL',
        ]);

        if (!$employeeTypeId || !$schoolId) {
            return;
        }

        $policies = FurloughPolicy::where('school_id', $schoolId)
            ->where('employee_type_id', $employeeTypeId)
            ->get();

        Log::info('Số Policy tìm thấy: ' . $policies->count());

        foreach ($policies as $policy) {
            try {
                $balance = FurloughBalance::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'furlough_type_id' => $policy->furlough_type_id,
                    ],
                    [
                        'total_days' => $policy->max_days ?? 0,
                        'used_days' => 0,
                        'remaining_days' => 0,
                        'last_accrual_at' => null,
                        'last_reset_at' => null,
                    ]
                );
                
                Log::info('✅ Đã tạo Balance ID: ' . $balance->id);
            } catch (\Throwable $e) {
                Log::error('❌ LỖI TẠO BALANCE: ' . $e->getMessage());
            }
        }
    }
}
