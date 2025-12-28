<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() > 0) {
            foreach (User::all() as $user) {
                if (!$user->userProfile) {
                    $user->userProfile()->create();
                }
            }
        }
    }
}
