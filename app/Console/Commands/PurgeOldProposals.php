<?php

namespace App\Console\Commands;

use App\Services\Settings\PurgeTempProposals;
use Illuminate\Console\Command;

class PurgeOldProposals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-proposals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge temp proposals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $purge = new PurgeTempProposals();
        $purge->cleanUpOld();
    }
}
