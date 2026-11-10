<?php

namespace App\Livewire\Pp;

use App\Mail\ProposalDeleted;
use App\Models\ProjectProposal;
use App\Services\Proposal\AdminProposalWorkflow;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;
use Workflow\Middleware\WithoutOverlappingMiddleware;
use Workflow\Models\StoredWorkflow;
use Workflow\States\WorkflowPendingStatus;
use Workflow\WorkflowStub;

class DeleteProposal extends Component
{
    #[Locked]
    public string $proposalId;

    public function delete(): void
    {
        $lock = null;
        $job = new \stdClass;
        try {
            DB::transaction(function () use (&$lock, $job) {
                $proposal = ProjectProposal::query()->lockForUpdate()->findOrFail($this->proposalId);
                $dashboard = $proposal->dashboard()->lockForUpdate()->first();
                $proposal->setRelation('dashboard', $dashboard);
                abort_unless($proposal->allowDelete(), 403, 'This proposal is not eligible for deletion.');

                $user = $proposal->submitter()->firstOrFail();
                $mail = new ProposalDeleted($user->name, $proposal->name, $proposal->id, $proposal->created_at->format('Y-m-d'));
                $recipient = $user->email;
                $id = $proposal->id;

                if ($dashboard?->workflow_id) {
                    $stored = config('workflows.stored_workflow_model', StoredWorkflow::class)::find($dashboard->workflow_id);
                    if (! $stored || ! in_array($stored->class, AdminProposalWorkflow::WORKFLOWS, true)) {
                        throw ValidationException::withMessages(['delete' => 'The linked proposal workflow could not be found.']);
                    }
                    $stored = $stored->active();
                    $candidate = new WithoutOverlappingMiddleware($stored->id, WithoutOverlappingMiddleware::WORKFLOW, 0, 60);
                    if (! $candidate->lock($job)) {
                        throw ValidationException::withMessages(['delete' => 'The workflow is currently processing. Please try again shortly.']);
                    }
                    $lock = $candidate;
                    $workflow = WorkflowStub::fromStoredWorkflow($stored->refresh());
                    if ($workflow->running()) {
                        if ($workflow->created()) {
                            $stored->status->transitionTo(WorkflowPendingStatus::class);
                        }
                        $workflow->fail(new RuntimeException('Proposal deleted by '.auth()->id()));
                    }
                }

                $dashboard?->delete();
                $proposal->delete();

                DB::afterCommit(function () use ($mail, $recipient, $id) {
                    Storage::deleteDirectory(ProposalsDirectory::MAIN.$id);
                    Mail::to($recipient)->send($mail);
                });
            });
        } finally {
            $lock?->unlock($job);
        }

        $this->dispatch('pp-update');
    }

    public function render()
    {
        return view('livewire.pp.delete-proposal');
    }
}
