<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearFundingOrgs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-funding';

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
        DB::table('funding_organizations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Run a specific seeder
        $this->call('db:seed', [
            '--class' => 'FundingOrganizationSeeder',
            '--force' => true,
        ]);

        $this->info('Funding organizations restored!');
    }
}
