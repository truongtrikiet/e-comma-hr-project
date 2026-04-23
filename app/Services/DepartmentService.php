<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function __construct(
        protected DepartmentRepositoryInterface $departmentRepository,
    ) {
        //
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $department = $this->departmentRepository->create($data);

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? null);

            if (is_array($data) && array_key_exists('user_ids', $data) && is_array($data['user_ids']) && count($data['user_ids']) > 0) {
                $this->addUser($department, ['user_ids' => $data['user_ids']]);
            }

            DB::commit();

            return $department;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function update($department, $data)
    {
        try {
            DB::beginTransaction();

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? $department->school_id);

            $updatedDepartment = $this->departmentRepository->update($department, $data);

            if (is_array($data) && array_key_exists('user_ids', $data)) {
                $ids = is_array($data['user_ids']) ? array_values($data['user_ids']) : [$data['user_ids']];

                User::where('department_id', $department->id)
                    ->whereNotIn('id', $ids)
                    ->update(['department_id' => null]);

                if (count($ids) > 0) {
                    User::whereIn('id', $ids)->update(['department_id' => $department->id]);
                }
            }

            if (is_array($data) && array_key_exists('head_user_id', $data) && $data['head_user_id']) {
                $headId = $data['head_user_id'];
                $headUser = User::find($headId);
                if ($headUser && $headUser->department_id != $department->id) {
                    $headUser->department_id = $department->id;
                    $headUser->save();
                }
            }

            DB::commit();

            return $updatedDepartment;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
