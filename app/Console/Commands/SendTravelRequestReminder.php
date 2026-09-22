<?php

namespace App\Console\Commands;

use App\Jobs\ReminderEmails\SendTravelRequestReminderEmail;
use App\Models\TravelRequest;
use Illuminate\Console\Command;

class SendTravelRequestReminder extends Command
{
    protected $signature = 'send-travel-request-reminders
        {--dry-run : Don’t dispatch jobs, just show what would happen}
        {--limit=500 : Max travel requests to process}';

    protected $description = 'Dispatch travel request reminders to the project leader, head or finance officers.';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        if ($limit < 1) {
            $this->error('The limit must be greater than zero.');

            return self::INVALID;
        }

        $dryRun = (bool) $this->option('dry-run');
        $count = 0;

        TravelRequest::query()
            ->where('reminder', true)
            ->whereHas('dashboard', fn ($query) => $query->whereIn('state', array_keys(SendTravelRequestReminderEmail::ROLES)))
            ->with('dashboard')
            ->limit($limit)
            ->chunkById(200, function ($requests) use ($dryRun, &$count) {
                foreach ($requests as $request) {
                    $state = (string) $request->dashboard->state;
                    $type = SendTravelRequestReminderEmail::ROLES[$state];

                    if ($request->last_reminder_type === $type
                        && $request->last_reminder_sent_at?->greaterThan(now()->subDays(3))) {
                        continue;
                    }

                    $count++;
                    if ($dryRun) {
                        $this->line("DRY RUN: would dispatch reminder for travel request #{$request->id} ({$type})");

                        continue;
                    }

                    SendTravelRequestReminderEmail::dispatch($request->id, $state);
                    $request->forceFill([
                        'last_reminder_type' => $type,
                        'last_reminder_sent_at' => now(),
                    ])->save();
                }
            });

        $this->info("Processed. Dispatched (or would dispatch) {$count} jobs.");

        return self::SUCCESS;
    }
}
