<?php

namespace App\Jobs;

use App\Mail\SentNotificationRecipient;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSentNotificationRecipients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected User $user,
        protected Dashboard $dashboard,
        protected array $recipients,
    ) {}

    public function handle(): void
    {
        if (empty($this->recipients)) {
            return;
        }

        collect($this->recipients)
            ->filter(fn (array $recipient) => filter_var($recipient['email'] ?? null, FILTER_VALIDATE_EMAIL))
            ->unique(fn (array $recipient) => strtolower($recipient['email']))
            ->each(function (array $recipient) {
                Mail::to($recipient['email'])->send(
                    new SentNotificationRecipient($this->user, $recipient, $this->dashboard)
                );
            });
    }
}
