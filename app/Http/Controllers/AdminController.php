<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use Illuminate\Http\RedirectResponse;
use Statamic\View\View as StatamicView;
use Workflow\WorkflowStub;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth', 'dsv']);
        // TODO an 'admin' middleware only admins should access this.
        // $this->middleware('admin');
    }

    public function pp()
    {
        $viewData = [
            'proposals' => ProjectProposal::query()
                ->where('status_stage3', '!=', 'pending')
                ->paginate(10),
            'breadcrumb' => 'Admin',
        ];

        return (new StatamicView)
            ->template('pp.admin.index')
            ->layout('mylayout')
            ->with($viewData);
    }

    public function pp_delete($id): RedirectResponse
    {
        $proposal = ProjectProposal::findOrFail($id);

        $dashboard = Dashboard::where('request_id', $proposal->id)->first();

        // If you have an "archived" column, prefer that.
        // Adjust these fields to match your actual schema.
        $proposal->status_stage3 = 'archived';
        $proposal->save();

        if ($dashboard && $dashboard->workflow_id) {
            //TODO
            //$workflow = WorkflowStub::load($dashboard->workflow_id);
            dd('Work in progress');
        }

        return redirect()->back()->with('success', 'Your Project proposal has successfully been archived!');
    }
}

