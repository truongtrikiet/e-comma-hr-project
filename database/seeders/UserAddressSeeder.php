<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count()) {
            foreach (User::all() as $user) {
                if (!$user->address) {
                    $user->address()->create();
                }
            }
        }
    }
}
