<?php

namespace Database\Seeders;

use App\Imports\ResearchAreaImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ResearchAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Restore default research areas
        Excel::import(new ResearchAreaImport, 'researcharea.xlsx');
    }
}
