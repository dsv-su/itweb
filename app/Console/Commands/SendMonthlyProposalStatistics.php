<?php

namespace App\Console\Commands;

use App\Mail\MonthlyProposalStatistics;
use App\Models\SettingsVice;
use App\Services\Stats\MonthlyProposalStats;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMonthlyProposalStatistics extends Command
{
    protected $signature = 'proposals:send-monthly-statistics {--month= : Report month in YYYY-MM format; defaults to the previous calendar month}
        {--dry-run : Preview the month and recipient count without sending}';

    protected $description = 'Email the previous calendar month’s proposal statistics to configured SUKAT recipients';

    public function handle(MonthlyProposalStats $statistics): int
    {
        $requestedMonth = $this->option('month');
        if ($requestedMonth !== null && ! preg_match('/\A[1-9][0-9]{3}-(0[1-9]|1[0-2])\z/', $requestedMonth)) {
            $this->error('The --month option must be a valid month in YYYY-MM format (for example, 2026-08).');

            return self::INVALID;
        }

        $month = $requestedMonth !== null
            ? CarbonImmutable::createFromFormat('!Y-m', $requestedMonth, 'Europe/Stockholm')
            : CarbonImmutable::now('Europe/Stockholm')->startOfMonth()->subMonth();
        $recipients = collect(SettingsVice::first()?->monthly_stats_recipients ?? [])
            ->filter(fn ($recipient) => is_array($recipient) && filter_var($recipient['email'] ?? '', FILTER_VALIDATE_EMAIL))
            ->map(fn ($recipient) => array_replace($recipient, ['email' => strtolower(trim($recipient['email']))]))
            ->unique('email');

        if ($this->option('dry-run') || $recipients->isEmpty()) {
            $this->info($month->format('F Y').': '.$recipients->count().' configured recipients. No emails sent.');

            return self::SUCCESS;
        }

        $lock = Cache::lock('monthly-proposal-statistics:'.$month->format('Y-m'), 3600);
        if (! $lock->get()) {
            $this->warn('Monthly statistics delivery is already running.');

            return self::FAILURE;
        }

        try {
            $stats = $statistics->build($month);
            $failed = false;
            $sent = 0;
            foreach ($recipients as $recipient) {
                $key = ['month' => $month->toDateString(), 'email' => $recipient['email']];
                if (DB::table('monthly_proposal_stat_deliveries')->where($key)->whereNotNull('sent_at')->exists()) {
                    continue;
                }

                try {
                    Mail::to($recipient['email'])->send(new MonthlyProposalStatistics($stats, $recipient));
                    DB::table('monthly_proposal_stat_deliveries')->updateOrInsert($key, ['sent_at' => now()]);
                    $sent++;
                } catch (Throwable $exception) {
                    report($exception);
                    $failed = true;
                    $this->error('A recipient delivery failed; see the application log. Rerun to retry unsent recipients.');
                }
            }
            $this->info("Sent {$sent} monthly statistics emails for ".$stats['month'].'.');

            return $failed ? self::FAILURE : self::SUCCESS;
        } finally {
            $lock->release();
        }
    }
}
