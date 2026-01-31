<?php

namespace App\Services;

use App\Models\School;
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

            DB::commit();

            return $updatedDepartment;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}