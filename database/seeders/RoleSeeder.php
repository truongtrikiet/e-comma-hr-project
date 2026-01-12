php<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super admin',
            'admin',
            'staff',
            'teacher',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
