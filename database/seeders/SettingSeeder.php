<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'user_id' => '1',
            'darkMode' => false,
            'transition' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Setting::create([
            'user_id' => '2',
            'darkMode' => false,
            'transition' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Setting::create([
            'user_id' => '3',
            'darkMode' => false,
            'transition' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Setting::create([
            'user_id' => '4',
            'darkMode' => false,
            'transition' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
