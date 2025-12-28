<?php

namespace Database\Seeders;

use App\Acl\Acl;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() > 0) {
            return;
        }

        $superAdmin = User::withoutEvents(function () {
            return User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@ecomma.vn',
                'password' => Hash::make('abcD@123'),
            ]);
        });

        $admin = User::withoutEvents(function () {
            return User::create([
                'name' => 'Admin',
                'email' => 'admin@ecomma.vn',
                'password' => Hash::make('abcD@123'),
            ]);
        });

        $staff = User::withoutEvents(function () {
            return User::create([
                'name' => 'Staff',
                'email' => 'staff@ecomma.vn',
                'password' => Hash::make('abcD@123'),
            ]);
        });

        $teacher = User::withoutEvents(function () {
            return User::create([
                'name' => 'Teacher',
                'email' => 'teacher@ecomma.vn',
                'password' => Hash::make('abcD@123'),
            ]);
        });

        $superAdminRole = Role::findByName(Acl::ROLE_SUPER_ADMIN);
        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $staffRole = Role::findByName(Acl::ROLE_STAFF);
        $teacherRole = Role::findByName(Acl::ROLE_TEACHER);

        $superAdmin->syncRoles($superAdminRole);
        $admin->syncRoles($adminRole);
        $staff->syncRoles($staffRole);
        $teacher->syncRoles($teacherRole);
    }
}
