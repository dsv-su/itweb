<?php

namespace App\Jobs\ReminderEmails;

use App\Mail\Reminders\InactiveProposalReminder;
use App\Models\ProjectProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInactiveProposalReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $proposalId) {}

    public function handle(): void
    {
        $proposal = ProjectProposal::with(['dashboard', 'submitter'])->find($this->proposalId);
        if (! $proposal || ! $proposal->reminder || $proposal->status_stage3 === 'pending'
            || ! $proposal->isInactiveDeletableProposal()) {
            return;
        }

        $user = $proposal->submitter;
        if (! $user || ! $user->email) {
            return;
        }

        Mail::to($user->email)->send(new InactiveProposalReminder(
            $user->name, $proposal->name, $proposal->id,
            $proposal->created_at->format('Y-m-d'), $proposal->updated_at->format('Y-m-d'),
        ));
    }
}
