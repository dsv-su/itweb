<?php

namespace App\Bus\Middleware;

use App\Models\ProjectProposal;
use App\Services\Proposal\AdminProposalWorkflow;
use Closure;
use Workflow\Activity;

class SkipEndedProposalJobs
{
    public function handle($command, Closure $next)
    {
        // Queued activities can outlive a failed workflow. Check after their queue lock is acquired.
        if ($command instanceof Activity
            && in_array($command->storedWorkflow->class, AdminProposalWorkflow::WORKFLOWS, true)
            && $command->storedWorkflow->fresh()?->toWorkflow()->failed()) {
            return null;
        }

        if (in_array($command::class, [
            \App\Jobs\ReminderEmails\SendCompleteReminderEmail::class,
            \App\Jobs\ReminderEmails\SendUHReminderEmail::class,
            \App\Jobs\ReminderEmails\SendFOReminderEmail::class,
            \App\Jobs\ReminderEmails\SendVHReminderEmail::class,
            \App\Jobs\ReminderEmails\SendUserReportSentReminderEmail::class,
            \App\Jobs\ReminderEmails\SendUserReportGrantRejectReminderEmail::class,
        ], true) && ! ProjectProposal::whereKey($command->proposalId)->where('reminder', true)->exists()) {
            return null;
        }

        return $next($command);
    }
}
