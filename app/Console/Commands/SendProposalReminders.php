<?php

namespace App\Console\Commands;

use App\Jobs\ReminderEmails\SendCompleteReminderEmail;
use App\Jobs\ReminderEmails\SendFOReminderEmail;
use App\Jobs\ReminderEmails\SendUHReminderEmail;
use App\Jobs\ReminderEmails\SendUserReportGrantRejectReminderEmail;
use App\Jobs\ReminderEmails\SendUserReportSentReminderEmail;
use App\Jobs\ReminderEmails\SendVHReminderEmail;
use App\Models\ProjectProposal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendProposalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-proposal-reminders
        {--dry-run : Don’t dispatch jobs, just show what would happen}
        {--limit=500 : Max proposals to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch reminder email jobs based on stage statuses.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit  = (int) $this->option('limit');

        $query = ProjectProposal::query()
            ->limit($limit);

        $count = 0;

        $query->chunkById(200, function ($proposals) use (&$count, $dryRun) {
            foreach ($proposals as $proposal) {
                $action = $this->decideAction($proposal);

                if (!$action) {
                    continue;
                }

                // don’t repeat same reminder within 3 days
                if ($this->recentlySent($proposal, $action['type'], days: 3)) {
                    continue;
                }

                $count++;

                if ($dryRun) {
                    $this->line("DRY RUN: would dispatch {$action['job']} for proposal #{$proposal->id} ({$action['type']})");
                    continue;
                }

                // dispatch the job
                dispatch(new $action['job']($proposal->id));

                // update tracking
                $proposal->forceFill([
                    'last_reminder_type' => $action['type'],
                    'last_reminder_sent_at' => now(),
                ])->save();
            }
        });

        $this->info("Processed. Dispatched (or would dispatch) {$count} jobs.");
        return self::SUCCESS;
    }

    private function decideAction(ProjectProposal $proposal): ?array
    {
        $s1 = $proposal->status_stage1;
        $s2 = $proposal->status_stage2;
        $s3 = $proposal->status_stage3;

        // User complete
        if ($s1 === 'submitted' && $s2 === 'pending' && $s3 === 'submitted') {
            return [
                'type' => 'REMINDER_USER_TO_COMPLETE',
                'job'  => SendCompleteReminderEmail::class,
            ];
        }

        // Unit Head reminder
        if ($s1 === 'submitted' && $s2 === 'uploaded' && $s3 === 'submitted') {
            return [
                'type' => 'REMIND_UH_TO_PROCESS',
                'job'  => SendUHReminderEmail::class,
            ];
        }

        // FO reminder
        if ($s1 === 'head_approved' && $s2 === 'uploaded' && $s3 === 'submitted') {
            return [
                'type' => 'REMIND_FO_TO_PROCESS',
                'job'  => SendFOReminderEmail::class,
            ];
        }

        // Vice reminder
        if ($s1 === 'fo_approved' && $s2 === 'fo_approved' && $s3 === 'submitted') {
            return [
                'type' => 'REMIND_VICE_TO_PROCESS',
                'job'  => SendVHReminderEmail::class,
            ];
        }

        // User reminder to Report Sent
        if ($s1 === 'final_approved' && $s2 === 'final_approved' && $s3 === 'submitted') {
            return [
                'type' => 'REMIND_USER_TO_REPORT_SENT',
                'job'  => SendUserReportSentReminderEmail::class,
            ];
        }

        // User reminder to Report Granted/Rejected
        if ($s1 === 'sent' && $s2 === 'final_approved' && $s3 === 'submitted') {
            return [
                'type' => 'REMIND_USER_TO_REPORT_GRANTED_OR_REJECTED',
                'job'  => SendUserReportGrantRejectReminderEmail::class,
            ];
        }

        return null;
    }

    private function recentlySent(ProjectProposal $proposal, string $type, int $days = 3): bool
    {
        if (!$proposal->last_reminder_sent_at) {
            return false;
        }

        if ($proposal->last_reminder_type !== $type) {
            return false;
        }

        return Carbon::parse($proposal->last_reminder_sent_at)->greaterThan(now()->subDays($days));
    }

}
