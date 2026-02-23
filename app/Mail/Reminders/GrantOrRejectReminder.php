<?php

namespace App\Mail\Reminders;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GrantOrRejectReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Dashboard $dashboard
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposals'),
            subject: 'Reminder: Report '. Str::limit($this->dashboard->name, 28),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.reminders.grant_or_reject',
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
