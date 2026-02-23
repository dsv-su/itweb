<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\DsvBudget;
use App\Models\ProjectProposal;
use App\Models\ResearchArea;
use App\Models\SettingsFo;
use App\Models\TravelRequest;
use App\Models\User;
use App\Services\Review\DashboardRole;
use App\Services\Review\RequestReviewHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Statamic\View\View as StatamicView;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth', 'dsv']);
        $this->middleware('review')->except(['pp_view']);
    }

    public function pp_view(ProjectProposal $proposal)
    {
        return $this->renderProjectProposal($proposal, 'view');
    }

    public function pp_review(ProjectProposal $proposal)
    {
        return $this->renderProjectProposal($proposal, 'review');
    }

    private function renderProjectProposal($proposal, string $mode)
    {
        $dashboard = $proposal->dashboard;
        $viewData = $this->prepareProjectProposalData() + [
                'proposal'   => $proposal,
                'dashboard'  => $dashboard,
                'type'       => $mode,
            ];

        if ($mode === 'review') {
            $viewData += [
                'budget'   => DsvBudget::find(1),
                'reviewer' => auth()->user(),
                'role'     => $this->parseRole($dashboard),
            ];
        }

        return $this->createView('pp.create', 'mylayout', $viewData);
    }

    private function prepareProjectProposalData(): array
    {
        $unitHeadIds = $this->getUserIdsByGroup('enhetschef');

        return [
            'unitheads'      => User::whereIn('id', $unitHeadIds)->get(),
            'research_areas' => ResearchArea::all(),
        ];
    }

    private function getUserIdsByGroup($groupIdOrSlug): array
    {
        return DB::table('group_user')
            ->where('group_id', $groupIdOrSlug)
            ->pluck('user_id')
            ->all();
    }

    private function createView($template, $layout, array $data)
    {
        return (new \Statamic\View\View)
            ->template($template)
            ->layout($layout)
            ->with($data);
    }

    public function show($id)
    {
        $dashboard = Dashboard::findOrFail($id);
        $tr = TravelRequest::findOrFail($dashboard->request_id);

        $user = auth()->user();
        $fo = SettingsFo::find(1);

        $formtype = ($fo && $user->id === $fo->user_id) ? 'fo_review' : 'review';

        return (new StatamicView)
            ->template('requests.travel.show')
            ->layout('mylayout')
            ->with([
                'tr' => $tr,
                'formtype' => $formtype,
                'dashboard' => $dashboard,
            ]);
    }

    public function review(Request $request, $req)
    {
        return $this->handleReview($request, $req, false);
    }

    public function fo_review(Request $request, $req)
    {
        return $this->handleReview($request, $req, true);
    }

    private function handleReview(Request $request, $dashboardId, bool $isFo)
    {
        $dashboard = Dashboard::findOrFail($dashboardId);

        if ($isFo) {
            $this->applyFoUpdates($request, $dashboard);
        }

        $user = auth()->user();

        $comment = trim(
            (string) ($request->input('comment_mobile') ?: $request->input('comment'))
        );

        $decision = $request->input('decision');

        $handler = new RequestReviewHandler($dashboard, $user, $comment, $decision);
        $handler->review();

        return redirect('/')->with('status', 'Request updated');
    }

    private function applyFoUpdates(Request $request, Dashboard $dashboard): void
    {
        if ($dashboard->type === 'travelrequest') {
            $tr = TravelRequest::findOrFail($dashboard->request_id);
            $tr->project = $request->input('project');
            $tr->save();
        }
    }

    public function parseRole($dashboard)
    {
        $dashboardrole = new DashboardRole($dashboard, auth()->user());
        $role = $dashboardrole->check();

        $map = [
            'vice_final' => 'Final Approval',
            'vice'       => 'Vice Approval',
            'head'       => 'Head Approval',
            'fo'         => 'FO Approval',
        ];

        return $map[$role] ?? 'N/A';
    }
}
