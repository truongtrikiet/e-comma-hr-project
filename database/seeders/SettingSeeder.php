<?php

namespace Database\Seeders;

use App\Models\Contracts\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingKey::allKeys() as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
