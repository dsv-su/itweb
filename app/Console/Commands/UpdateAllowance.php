<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\Skatteverket;
use Illuminate\Console\Command;

class UpdateAllowance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-allowance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $year = now()->year;
        $skatteverket = new Skatteverket;

        Country::query()->truncate();
        $skatteverket->getCountry($year);
        $skatteverket->checkAllowance();

        return self::SUCCESS;
    }
}
