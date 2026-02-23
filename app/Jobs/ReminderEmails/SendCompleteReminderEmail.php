<?php

namespace App\Jobs\ReminderEmails;

use App\Models\ProjectProposal;
use App\Models\User;
use App\Mail\Reminders\CompleteReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCompleteReminderEmail implements ShouldQueue
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

        Mail::to($user->email)->send(new CompleteReminder($user, $dashboard));
    }
}
