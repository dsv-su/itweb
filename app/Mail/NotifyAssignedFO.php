<?php

namespace App\Mail;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NotifyAssignedFO extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $fo, $vice, $dashboard;

    public function __construct(User $fo, Dashboard $dashboard)
    {
        $this->fo = $fo;
        $this->dashboard = $dashboard;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposals'),
            subject: Str::upper($this->dashboard->type) . ' Assigned Proposal: '. Str::limit($this->dashboard->name, 28),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.assignedfo',
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
