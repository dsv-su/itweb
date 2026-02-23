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

class UHReminder extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(
        public User $head,
        public User $user,
        public Dashboard $dashboard
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposals'),
            subject: 'Reminder: Review '. Str::limit($this->dashboard->name, 28),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.reminders.uh',
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
