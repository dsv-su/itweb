<?php

namespace App\Mail\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InactiveProposalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $proposalName,
        public string $proposalId,
        public string $createdDate,
        public string $updatedDate,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposals'),
            subject: 'Reminder: Inactive proposal '.Str::limit($this->proposalName, 28),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.projectproposal.reminders.inactive');
    }
}
