<?php

namespace App\Services;

use App\Acl\Acl;
use App\Models\School;
use App\Repositories\Role\RoleRepositoryInterface;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
    ) {
        //
    }

    /**
     * Get list of roles except super admin role
     */
    public function getListExceptSuperAdmin()
    {
        $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
        $systemId = $defaultSystem->id ?? null;

        $currentUser = auth()->user();

        $roles = $this->roleRepository->allRolesWithPermissions();

        if ($currentUser && $currentUser->school_id && $currentUser->school_id !== $systemId) {
            return $roles->reject(fn($role) => ($role->name ?? '') === Acl::ROLE_SUPER_ADMIN)->values();
        }

        return $roles;
    }
}