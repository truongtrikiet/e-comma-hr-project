<?php

namespace Database\Seeders;

use App\Acl\Acl;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserUpdateNewRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->updateUserByRole('super admin', Acl::ROLE_SUPER_ADMIN);
        $this->updateUserByRole('admin', Acl::ROLE_ADMIN);
        $this->updateUserByRole('staff', Acl::ROLE_STAFF);
        $this->updateUserByRole('teacher', Acl::ROLE_TEACHER);
    }

    /**
     * Update user by input role.
     */
    private function updateUserByRole($role, $newRole): void
    {
        $userWithRoles = User::role($role)->get();
        foreach ($userWithRoles as $user) {
            $user->syncRoles([$newRole]);
        }
    }
}
