<?php

namespace App\Jobs\ReminderEmails;

use App\Mail\Reminders\UHReminder;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendUHReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $proposalId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $proposal = ProjectProposal::findOrFail($this->proposalId);
        $dashboard = $proposal->dashboard;
        $user = User::findOrFail($dashboard->user_id);

        // unit_head_approved
        $heads = json_decode($dashboard->unit_head_approved ?? '{}', true);

        // uuids that are still 0
        $pendingUuids = collect($heads)
            ->filter(fn ($status) => (int) $status === 0)
            ->keys()
            ->values();

        // fetch users
        $pendingHeads = User::whereIn('id', $pendingUuids)->get();

        foreach ($pendingHeads as $head) {
            Mail::to($head->email)->send(new UHReminder($head, $user, $dashboard));
        }
    }
}
