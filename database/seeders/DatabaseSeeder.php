<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguard(); // Disable mass assignment
        $this->call(ProjectImportSeeder::class);
        $this->call(DailyAllowanceSeeder::class);
        $this->call(FundingOrganizationSeeder::class);
        $this->call(ResearchAreaSeeder::class);
        Model::reguard(); // Enable mass assignment

    }
}
