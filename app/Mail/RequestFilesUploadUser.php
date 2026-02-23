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

class RequestFilesUploadUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $user, $head, $vice, $dashboard;

    public function __construct(User $user, User $head, User $vice, Dashboard $dashboard)
    {
        $this->user = $user;
        $this->head = $head;
        $this->vice = $vice;
        $this->dashboard = $dashboard;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'DSVIntranet'),
            subject: Str::limit($this->dashboard->name, 28) .
            ' - budgetfiles request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.user_files_request',
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
