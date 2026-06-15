<?php

namespace App\Mail;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GrantNotificationRecipient extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public array $recipient,
        public Dashboard $dashboard,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposals'),
            subject: 'Grant approved: '.Str::limit($this->dashboard->name, 28),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.grant-notification-recipient',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
