<?php

namespace App\Console\Commands;

use Database\Seeders\SettingsOhsSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearBudget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-budget';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('research_areas')->truncate();
        DB::table('dsv_budgets')->truncate();
        DB::table('settings_ohs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Run Research Subject seeder
        $this->call('db:seed', [
            '--class' => 'ResearchAreaSeeder',
            '--force' => true,
        ]);
        // Run DSV Budget seeder
        $this->call('db:seed', [
            '--class' => 'DsvBudgetsSeeder',
            '--force' => true,
        ]);
        // Run OH seeder
        $this->call(SettingsOhsSeeder::class);

    }
}
