<?php

namespace App\Jobs\ReminderEmails;

use App\Mail\Reminders\TravelRequestReminder;
use App\Models\TravelRequest;
use App\Models\User;
use App\Services\Finance\FinancialOfficerRecipients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTravelRequestReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const ROLES = [
        'submitted' => 'manager',
        'manager_approved' => 'head',
        'head_approved' => 'fo',
    ];

    public function __construct(public string $requestId, public string $state) {}

    public function handle(): void
    {
        $request = TravelRequest::with('dashboard.user')->find($this->requestId);
        $dashboard = $request?->dashboard;
        $role = self::ROLES[$this->state] ?? null;

        // A queued reminder may outlive the approval stage that scheduled it.
        if (! $request?->reminder || ! $dashboard?->user || ! $role
            || (string) $dashboard->state !== $this->state) {
            return;
        }

        $recipients = $role === 'fo'
            ? FinancialOfficerRecipients::forDashboard($dashboard)
            : User::whereKey($dashboard->{$role.'_id'})->get();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new TravelRequestReminder($recipient, $dashboard->user, $dashboard));
        }
    }
}
