<?php

namespace App\Services\Proposal;

use App\Models\ProjectProposal;
use App\Workflows\DSVProjectPWorkflow;
use App\Workflows\ResumeFromFOProjectWorkflow;
use App\Workflows\ResumeFromFinalProjectWorkflow;
use App\Workflows\ResumeFromUHProjectWorkflow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Workflow\Middleware\WithoutOverlappingMiddleware;
use Workflow\Models\StoredWorkflow;
use Workflow\States\WorkflowPendingStatus;
use Workflow\WorkflowStub;

class AdminProposalWorkflow
{
    public const STATES = [
        'pending' => 'Pending',
        'submitted' => 'Awaiting completion',
        'complete' => 'Submitted for review',
        'head_approved' => 'Head approved',
        'head_returned' => 'Head returned',
        'head_denied' => 'Head denied',
        'fo_approved' => 'Finance approved',
        'fo_returned' => 'Finance returned',
        'fo_denied' => 'Finance denied',
        'final_approved' => 'Final approved',
        'final_returned' => 'Final returned',
        'final_denied' => 'Final denied',
        'sent' => 'Sent',
        'granted' => 'Granted',
        'denied' => 'Rejected',
    ];

    public const WORKFLOWS = [
        DSVProjectPWorkflow::class,
        ResumeFromUHProjectWorkflow::class,
        ResumeFromFOProjectWorkflow::class,
        ResumeFromFinalProjectWorkflow::class,
    ];

    public const RESUME_STAGES = [
        'head_returned' => 'Unit-head review',
        'fo_returned' => 'Finance review',
        'final_returned' => 'Final approval',
    ];

    public const RESUME_WORKFLOWS = [
        'head_returned' => ResumeFromUHProjectWorkflow::class,
        'fo_returned' => ResumeFromFOProjectWorkflow::class,
        'final_returned' => ResumeFromFinalProjectWorkflow::class,
    ];

    public function resume(ProjectProposal $proposal, string $actor, string $stage, ?int $expectedWorkflowId): void
    {
        if (! array_key_exists($stage, self::RESUME_WORKFLOWS)) {
            throw ValidationException::withMessages(['resume_stage' => 'Choose an available resume stage.']);
        }

        $this->updateWorkflow($proposal, $actor, 'submitted', $stage, $expectedWorkflowId);
    }

    public function end(ProjectProposal $proposal, string $actor, ?string $state = null): void
    {
        $this->updateWorkflow($proposal, $actor, $state);
    }

    private function updateWorkflow(ProjectProposal $proposal, string $actor, ?string $state, ?string $resumeStage = null, ?int $expectedWorkflowId = null): void
    {
        if ($state !== null && ! array_key_exists($state, self::STATES)) {
            throw ValidationException::withMessages(['state' => 'Choose an available proposal state.']);
        }

        $lock = null;
        $job = new \stdClass;

        try {
            DB::transaction(function () use ($proposal, $actor, $state, $resumeStage, $expectedWorkflowId, &$lock, $job) {
                $proposal = ProjectProposal::query()->lockForUpdate()->findOrFail($proposal->id);
                $dashboard = $proposal->dashboard()->lockForUpdate()->first();
                if (! $dashboard || $dashboard->type !== 'projectproposal') {
                    throw ValidationException::withMessages(['state' => 'This proposal has no proposal dashboard to update.']);
                }

                if ($resumeStage !== null && (string) $dashboard->workflow_id !== (string) $expectedWorkflowId) {
                    throw ValidationException::withMessages(['resume_stage' => 'The workflow has changed. Refresh the page before resuming.']);
                }

                if ($dashboard->workflow_id) {
                    $stored = config('workflows.stored_workflow_model', StoredWorkflow::class)::find($dashboard->workflow_id);
                    if (! $stored || ! in_array($stored->class, self::WORKFLOWS, true)) {
                        throw ValidationException::withMessages(['state' => 'The linked proposal workflow could not be found.']);
                    }
                    $stored = $stored->active();
                    $candidate = new WithoutOverlappingMiddleware($stored->id, WithoutOverlappingMiddleware::WORKFLOW, 0, 60);
                    if (! $candidate->lock($job)) {
                        throw ValidationException::withMessages(['state' => 'The workflow is currently processing. Please try again shortly.']);
                    }
                    $lock = $candidate;
                    $workflow = WorkflowStub::fromStoredWorkflow($stored->refresh());
                    if ($workflow->running()) {
                        // The library has no cancelled status; fail retains its history and prevents resumption.
                        if ($workflow->created()) {
                            $stored->status->transitionTo(WorkflowPendingStatus::class);
                        }
                        $workflow->fail(new RuntimeException('Proposal workflow ended by administrator '.$actor));
                    }
                }

                // Stop scheduled reminders as well as workflow activities.
                $proposal->reminder = false;
                if ($state !== null) {
                    // An administrative override deliberately bypasses normal approval transitions.
                    $dashboard->state = $state;
                    $dashboard->save();
                    $proposal->status_stage1 = $state;
                    $proposal->status_stage2 = match ($state) {
                        'pending', 'submitted' => 'pending',
                        'fo_approved', 'final_approved', 'final_returned', 'final_denied' => $state,
                        'sent', 'granted', 'denied' => 'final_approved',
                        default => 'uploaded',
                    };
                    $proposal->pp = array_merge($proposal->pp ?? [], ['status' => $state]);
                }
                if ($resumeStage !== null) {
                    $proposal->reminder = true;
                    $proposal->last_reminder_type = null;
                    $proposal->last_reminder_sent_at = null;
                    $proposal->status_stage2 = collect($proposal->files ?? [])->contains('type', 'draft') && collect($proposal->files ?? [])->contains('type', 'budget')
                        ? 'uploaded' : 'pending';
                    $proposal->status_stage3 = 'submitted';
                    $proposal->pp = array_merge($proposal->pp ?? [], ['status' => 'resumed']);
                    $dashboard->status = 'resumed';
                    if ($resumeStage === 'head_returned') {
                        $dashboard->unit_head_approved = collect($dashboard->unit_heads ?? [])
                            ->mapWithKeys(fn ($head) => [$head => 0])->toJson();
                    }
                    $replacement = WorkflowStub::make(self::RESUME_WORKFLOWS[$resumeStage]);
                    $dashboard->workflow_id = $replacement->id();
                    $dashboard->save();
                }
                $proposal->save();

                if (isset($replacement)) {
                    // Resume workflows start at submitted and check files themselves. Their queued
                    // execution is dispatched after commit; no submit signal is needed here.
                    $replacement->start($dashboard);
                }
            });
            Log::info($resumeStage === null ? 'Administrator ended a proposal workflow' : 'Administrator resumed a proposal workflow', [
                'proposal_id' => $proposal->id,
                'administrator_id' => $actor,
                'new_state' => $state,
                'resume_stage' => $resumeStage,
            ]);
        } finally {
            $lock?->unlock($job);
        }
    }
}
