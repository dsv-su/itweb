<?php

namespace Database\Seeders;

use App\Imports\ProjectImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ProjectImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new ProjectImport, 'projektinformation.xlsx');
    }
}
