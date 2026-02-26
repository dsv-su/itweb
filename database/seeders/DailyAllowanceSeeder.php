<?php

namespace Database\Seeders;

use App\Services\Skatteverket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skatteverket = new Skatteverket();
        $skatteverket->getCountry();
    }
}
