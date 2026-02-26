<?php

namespace Database\Seeders;

use App\Models\ResearchArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DsvBudgetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all research areas
        $researchAreas = ResearchArea::pluck('name')->toArray();

        // Create a JSON structure where each research area is an object with 'preapproved' = 0
        $researchAreaData = [];
        foreach ($researchAreas as $area) {
            $researchAreaData[$area] = [
                'preapproved' => 0,
                'budget_sek' => 0,
                'budget_eur' => 0,
                'budget_usd' => 0,
                'phd' => 0,
                'cost_sek' => 0,
                'cost_eur' => 0,
                'cost_usd' => 0,
                'granted_sek' => 0,
                'granted_eur' => 0,
                'granted_usd' => 0,
                'phd_promised' => 0,
            ];
        }

        // Insert into dsv_budgets table
        DB::table('dsv_budgets')->insert([
            'research_area' => json_encode($researchAreaData),
            'preapproved_total' => 0,
            'budget_dsv_total_sek' => 0,
            'budget_dsv_total_eur' => 0,
            'budget_dsv_total_usd' => 0,
            'budget_project_total_sek' => 0,
            'budget_project_total_eur' => 0,
            'budget_project_total_usd' => 0,
            'phd_total' => 0,
            'cost_total_sek' => 0,
            'cost_total_eur' => 0,
            'cost_total_usd' => 0,
            'granted_total_sek' => 0,
            'granted_total_eur' => 0,
            'granted_total_usd' => 0,
            'phd_promised' => 0,
            'cofinanced_total_sek' => 0,
            'cofinanced_total_eur' => 0,
            'cofinanced_total_usd' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
