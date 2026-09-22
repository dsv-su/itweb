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

class TravelRequestReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $recipient,
        public User $user,
        public Dashboard $dashboard,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'DSViT'),
            subject: 'Påminnelse / Reminder: '.Str::limit($this->dashboard->name, 28),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request.reminder',
            with: ['travelRequest' => $this->dashboard->travel],
        );
    }
}
