<?php

namespace App\Mail;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NotifyRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user, $dashboard;

    public function __construct(User $user, Dashboard $dashboard)
    {
        $this->user = $user;
        $this->dashboard = $dashboard;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@dsv.su.se', $this->dashboard->type === 'travelrequest' ? 'ITWebb' : 'ProjectProposals'),
            subject: $this->dashboard->type === 'travelrequest'
                ? 'Godkänd resebegäran / Approved travel request: '. Str::limit($this->dashboard->name, 28)
                : 'Approved: '. Str::limit($this->dashboard->name, 28),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request.approved',
            with: ['organizationChanges' => $this->organizationChanges()],
        );
    }

    private function organizationChanges(): array
    {
        if ($this->dashboard->type !== 'projectproposal' || (string) $this->dashboard->state !== 'final_approved') {
            return [];
        }

        $pp = ProjectProposal::find($this->dashboard->request_id)?->pp ?? [];
        $changes = [];
        foreach (['funding_organization', 'program', 'dsvcoordinating', 'other_coordination', 'eu', 'eu_wallenberg'] as $field) {
            $change = $pp['vice_organization_changes'][$field] ?? null;
            // Omit reverted edits and values subsequently replaced by another editor.
            if (!is_array($change) || !isset($change['label'], $change['before'], $change['after'])
                || (string) $change['before'] === (string) $change['after']
                || (string) ($pp[$field] ?? '') !== (string) $change['after']) {
                continue;
            }
            if (in_array($field, ['dsvcoordinating', 'eu', 'eu_wallenberg'], true)) {
                $change['before'] = ucfirst($change['before']);
                $change['after'] = ucfirst($change['after']);
            }
            $changes[] = $change;
        }

        return $changes;
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
