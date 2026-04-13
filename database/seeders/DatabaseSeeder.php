<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345'),
                'is_admin' => true,
                'is_super_admin' => true,
            ]
        );

        $defaultSettings = [
            ['key' => 'institution_name', 'value' => 'SALSA'],
            ['key' => 'logo_path', 'value' => 'branding/SALSA.png'],
            ['key' => 'service_hours_weekday_start', 'value' => '08:00'],
            ['key' => 'service_hours_weekday_end', 'value' => '16:00'],
            ['key' => 'service_hours_friday_start', 'value' => '08:00'],
            ['key' => 'service_hours_friday_end', 'value' => '15:00'],
            ['key' => 'service_hours_weekend_start', 'value' => '08.30'],
            ['key' => 'service_hours_weekend_end', 'value' => '12.00'],
            ['key' => 'main_content_image_interval_seconds', 'value' => '8'],
            ['key' => 'gallery_interval_seconds', 'value' => '6'],
            ['key' => 'marquee_messages', 'value' => ''],
            ['key' => 'marquee_duration_seconds', 'value' => '30'],
            ['key' => 'display_content_categories', 'value' => null],
            ['key' => 'display_background_color', 'value' => '#0B0D18'],
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
