<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserProfileSeeder::class);
        $this->call(UserUpdateNewRoleSeeder::class);
        // $this->call(RoleSeeder::class);
    }
}
