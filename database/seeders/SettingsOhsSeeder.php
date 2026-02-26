<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsOhsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings_ohs')->insert([
            'oh_max' => 60,
            'oh_vinnova' => 100,
            'oh_eu' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
