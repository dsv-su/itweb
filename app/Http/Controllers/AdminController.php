<?php

namespace App\Http\Controllers;

use App\Models\ProjectProposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Statamic\View\View as StatamicView;
use App\Services\Proposal\AdminProposalWorkflow;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth', 'dsv']);
        // Only HelpDesk should access this.
        $this->middleware('helpdesk');
    }

    public function pp(Request $request): StatamicView
    {
        $validated = $request->validate(['search' => ['nullable', 'string', 'max:200']]);
        $search = trim($validated['search'] ?? '');

        $viewData = [
            'proposals' => ProjectProposal::query()
                ->with(['dashboard', 'submitter'])
                ->where('status_stage3', '!=', 'pending')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $term = '%'.$search.'%';
                        $query->where('name', 'like', $term)
                            ->orWhere('id', 'like', $term)
                            ->orWhere('pp->principal_investigator', 'like', $term)
                            ->orWhere('pp->research_area', 'like', $term)
                            ->orWhere('pp->funding_organization', 'like', $term)
                            ->orWhereHas('submitter', fn ($query) => $query->where('name', 'like', $term));
                    });
                })
                ->orderByDesc('created')
                ->orderBy('id')
                ->paginate(10)
                ->appends(['search' => $search]),
            'search' => $search,
            'proposalStates' => AdminProposalWorkflow::STATES,
            'resumeStages' => AdminProposalWorkflow::RESUME_STAGES,
            'breadcrumb' => 'Admin',
        ];

        return (new StatamicView)
            ->template('pp.admin.index')
            ->layout('mylayout')
            ->with($viewData);
    }

    public function endProposalWorkflow(Request $request, ProjectProposal $proposal, AdminProposalWorkflow $workflow): RedirectResponse
    {
        $workflow->end($proposal, (string) $request->user()->getAuthIdentifier());

        return redirect()->back()->with('success', 'Proposal workflow ended. Its state is unchanged and reminders are disabled.');
    }

    public function resumeProposalWorkflow(Request $request, ProjectProposal $proposal, AdminProposalWorkflow $workflow): RedirectResponse
    {
        $validated = $request->validate([
            'resume_stage' => ['required', 'string', Rule::in(array_keys(AdminProposalWorkflow::RESUME_STAGES))],
            'workflow_id' => ['present', 'nullable', 'integer', 'min:1'],
        ]);
        $workflow->resume(
            $proposal,
            (string) $request->user()->getAuthIdentifier(),
            $validated['resume_stage'],
            isset($validated['workflow_id']) ? (int) $validated['workflow_id'] : null,
        );

        return redirect()->back()->with('success', 'Proposal workflow resumed for '.AdminProposalWorkflow::RESUME_STAGES[$validated['resume_stage']].'. Reminders are enabled; required files are checked before review continues.');
    }

    public function setProposalState(Request $request, ProjectProposal $proposal, AdminProposalWorkflow $workflow): RedirectResponse
    {
        $validated = $request->validate(['state' => ['required', 'string', Rule::in(array_keys(AdminProposalWorkflow::STATES))]]);
        $workflow->end($proposal, (string) $request->user()->getAuthIdentifier(), $validated['state']);

        return redirect()->back()->with('success', 'Proposal state updated. Any active workflow has been ended and reminders are disabled.');
    }
}
