<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProposalSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proposal-seed';

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
        // Seed proposals
        $this->call('db:seed', [
            '--class' => 'ProposalSeeder',
            '--force' => true,
        ]);
    }
}
