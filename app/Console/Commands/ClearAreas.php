<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearAreas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-areas';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Run a specific seeder
        $this->call('db:seed', [
            '--class' => 'ResearchAreaSeeder',
            '--force' => true,
        ]);
        $this->call('db:seed', [
            '--class' => 'DsvBudgetsSeeder',
            '--force' => true,
        ]);

        $this->info('Research areas restored!');
    }
}
