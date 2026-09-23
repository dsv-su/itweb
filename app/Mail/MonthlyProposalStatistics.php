<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyProposalStatistics extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $stats, public array $recipient) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', 'ProjectProposal'),
            subject: 'Project proposals · Monthly summary · '.$this->stats['month'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.projectproposal.monthly-statistics',
            text: 'emails.projectproposal.monthly-statistics-text',
        );
    }
}
