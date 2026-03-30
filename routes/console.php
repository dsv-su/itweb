<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('proposals:cleanup-abandoned --days=1')
    ->dailyAt('21:00');

Schedule::command('send-proposal-reminders')
    ->dailyAt('21:00');
