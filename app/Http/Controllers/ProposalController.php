<?php

namespace App\Http\Controllers;

use App\Jobs\SendFinalToRegistrator;
use App\Jobs\SendGrantToRegistrator;
use App\Mail\GrantNotificationVice;
use App\Mail\SentNotificationVice;
use App\Models\Dashboard;
use App\Models\DsvBudget;
use App\Models\ProjectProposal;
use App\Models\SettingsFo;
use App\Models\SettingsFoEu;
use App\Models\User;
use App\Services\Proposal\ProjectProposalCreateView;
use App\Workflows\States\HeadReturned;
use App\Workflows\States\FoReturned;
use App\Workflows\States\FinalReturned;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\Budget\Budget;
use App\Services\Budget\ReCalcBudget;
use App\Services\Review\DashboardRole;
use App\Services\Review\ProposalFileReviewService;
use App\Services\Review\WorkflowHandler;
use App\Services\Proposal\ProjectProposalPrepare;
use App\Services\Proposal\ProjectProposalFormService;
use App\Services\Send\FilesForRegistrator;
use App\Workflows\DSVProjectPWorkflow;
use App\Workflows\Partials\RequestStates;
use Workflow\WorkflowStub;


class ProposalController extends Controller
{
    public function __construct(
        private ProjectProposalPrepare $proposalPrepare,
        private ProjectProposalFormService $formService,
        private ProjectProposalCreateView $proposalCreateView,
    ) {
        $this->middleware(['web', 'auth', 'dsv']);
    }

    public function pp_edit(string $id)
    {
        return $this->formService->build($id, 'edit');
    }

    public function pp_resume(string $id)
    {
        return $this->formService->build($id, 'resume', function (&$viewData) {
            $viewData['budget'] = DsvBudget::find(1); // ->first() or config id
        });
    }

    public function pp_continue(string $id)
    {
        return $this->formService->build($id, 'saved');
    }
    public function pp_complete(string $id)
    {
        return $this->formService->build($id, 'complete');
    }

    public function upload(string $id)
    {
        return $this->formService->build($id, 'complete', function (&$viewData) {
            $viewData['upload'] = true;
        });
    }

    public function create()
    {
        $viewData = $this->proposalPrepare->prepareProjectProposalData();
        $viewData['type'] = 'preapproval';

        return $this->proposalCreateView->build('pp.create', 'mylayout', $viewData);
    }

    /***
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    /**
     * Submit
     */
    public function submit(Request $request)
    {
        $this->validateRequest($request);

        $userId = $request->user()->id;
        $submittedAt = now();
        $createdTs = $submittedAt->copy()->startOfDay()->timestamp;
        //dd($request->type);
        $type = strtolower(trim((string) $request->type));

        return match ($type) {
            'preapproval', 'saved' => $this->handlePreapproval($request, $userId, $submittedAt, $createdTs),
            'save'                => $this->handleSave($request, $userId, $submittedAt, $createdTs),
            'complete'            => $this->handleComplete($request, $userId, $submittedAt),
            'edit'                => $this->handleEdit($request, $userId, $submittedAt, $createdTs),
            'resume'              => $this->handleResume($request, $userId, $submittedAt, $createdTs),
            'sent'                => $this->handleSent($request, $submittedAt),
            'granted'             => $this->handleGranted($request, $submittedAt),
            'rejected'            => $this->handleRejected($request, $submittedAt),
            'review'              => $this->handleReview($request, $submittedAt),

            default => abort(422, 'Invalid submit type'),
        };
    }

    /* -------------------------------------------------------------------------
     | Handlers
     * ---------------------------------------------------------------------- */

    private function handlePreapproval(Request $request, string $userId, Carbon $submittedAt, int $createdTs)
    {
        return DB::transaction(function () use ($request, $userId, $submittedAt, $createdTs) {

            $pp = ProjectProposal::findOrFail($request->id);

            $pp->fill([
                'user_id' => $userId,
                'name' => $request->title,
                'created' => $createdTs,
                'status_stage1' => 'pending',
                'status_stage2' => 'pending',
                'status_stage3' => 'submitted',
                'pp' => $this->buildPpPayload($request, [
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'pending',
                ]),
            ])->save();

            $dashboard = $this->upsertDashboardWithUnitHeads(
                $pp,
                $request,
                $this->dashboardBaseData($pp, $request, $userId, $createdTs, 'unread')
            );

            // Start workflow and store workflow ID
            $this->createAndStartWorkflow($dashboard);

            // Check files
            $this->checkFileStatus($pp);

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your Project proposal draft has successfully been submitted!');
        });
    }

    private function handleSave(Request $request, string $userId, Carbon $submittedAt, int $createdTs)
    {
        return DB::transaction(function () use ($request, $userId, $submittedAt, $createdTs) {

            $pp = ProjectProposal::findOrFail($request->id);

            $pp->fill([
                'user_id' => $userId,
                'name' => $request->title,
                'created' => $createdTs,
                'status_stage1' => 'pending',
                'status_stage2' => 'pending',
                'status_stage3' => 'saved',
                'pp' => $this->buildPpPayload($request, [
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'saved',
                ]),
            ])->save();

            $dashboard = $this->upsertDashboardWithUnitHeads(
                $pp,
                $request,
                $this->dashboardBaseData($pp, $request, $userId, $createdTs, 'unread')
            );

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your Project proposal draft has successfully been saved!');
        });
    }

    private function handleComplete(Request $request, string $userId, Carbon $submittedAt)
    {
        $pp = ProjectProposal::query()->findOrFail($request->id);

        $updatedPp = $this->mergePp($pp->pp ?? [], [
            ...$request->only([
                'unit_head', 'program', 'decision_exp', 'funding_organization',
                'start_date', 'submission_deadline',
                'budget_project', 'budget_dsv', 'budget_phd', 'currency',
                'oh_cost', 'cofinancing_needed',
            ]),
            'submitted' => $submittedAt->toISOString(),
            'status' => 'completed',
            'co_investigator_name' => $request->co_investigator_name,
            'co_investigator_email' => $request->co_investigator_email,
            'co_investigator_type' => $request->co_investigator_type,
            'co_investigator_role' => $request->co_investigator_role,
        ]);

        $pp->update(['pp' => $updatedPp]);

        // Append-only comment input normalization
        $newComment = $request->input('comment') ?? $request->input('edit_comments');
        if ($newComment !== null && trim($newComment) !== '') {
            $this->comments_update($pp->id, $newComment, 'completed');
        }

        $dashboard = Dashboard::query()
            ->where('request_id', $pp->id)
            ->firstOrFail();

        $this->setUnitHeadsOnDashboard($dashboard, (array) $request->unit_head);

        if ($this->checkFiles($pp)) {
            (new WorkflowHandler($dashboard->workflow_id))->Completed();

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your Project proposal files have successfully been uploaded!');
        }

        return redirect()->route('pp.show', 'my')
            ->with('success', 'Your Project proposal has been updated!');
    }


    private function handleEdit(Request $request, string $userId, Carbon $submittedAt, int $createdTs)
    {
        $pp = ProjectProposal::query()->findOrFail($request->id);

        $existing = $pp->pp ?? [];
        $incoming = $this->buildPpPayload($request, [
            'submitted' => $submittedAt->toISOString(),
            'status'    => 'edited',
        ]);

        $pp->fill([
            'user_id' => $userId,
            'name'    => $request->title,
            'created' => $createdTs,
            'pp'      => $this->mergePp($existing, $incoming),
        ])->save();

        // append-only comment (safe)
        if ($request->filled('comment')) {
            $this->comments_update($pp->id, $request->comment, 'edit');
        }

        $dashboard = $this->upsertDashboardWithUnitHeads(
            $pp,
            $request,
            $this->dashboardBaseData($pp, $request, $userId, $createdTs, 'edited')
        );

        // Check files and transition
        switch ($dashboard->state) {
            case 'complete':
                if (! $this->checkFiles($pp)) {
                    (new WorkflowHandler($dashboard->workflow_id))->submitted();
                }
                break;
        }

        return redirect()->route('pp.show', 'my')->with('success', 'Proposal successfully updated!');
    }


    private function handleResume(Request $request, string $userId, Carbon $submittedAt, int $createdTs)
    {
        $pp = ProjectProposal::query()->findOrFail($request->id);

        $existing = $pp->pp ?? [];
        $incoming = $this->buildPpPayload($request, [
            // decide what resume should do
            'status'    => 'resumed',
            'submitted' => $submittedAt->toISOString(),
        ]);

        // Prevent overwriting comment history on resume (important)
        unset($incoming['user_comments']);

        $pp->fill([
            'user_id' => $userId,
            'name'    => $request->title,
            'created' => $createdTs,
            'pp'      => $this->mergePp($existing, $incoming),
        ])->save();

        //Log resumed
        $this->comments_update($pp->id, $request->comment, 'resumed');

        $dashboard = Dashboard::query()->updateOrCreate(
            ['request_id' => $pp->id],
            [
                'name'   => $request->title,
                'status' => 'resumed',
            ]
        );

        // async side effects (fine outside tx)
        $this->resumeWorkflow($dashboard);
        $this->checkFileStatus($pp);

        return redirect()->route('pp.show', 'my')->with('success', 'Proposal successfully resumed!');
    }


    private function handleSent(Request $request, Carbon $submittedAt)
    {
        return DB::transaction(function () use ($request, $submittedAt) {

            $pp = ProjectProposal::findOrFail($request->id);

            $files = is_array($pp->files ?? null) ? $pp->files : [];
            $finalFile = collect($files)->first(fn ($file) => isset($file['type']) && $file['type'] === 'final');

            if (!$finalFile) {
                return redirect()->route('pp.show', 'my')
                    ->with('error', 'Please make sure to upload your final application before the reporting');
            }

            $pp->update([
                'pp' => $this->mergePp($pp->pp ?? [], [
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'sent',
                ]),
                'status_stage1' => 'sent',
            ]);

            $dashboard = Dashboard::query()->where('request_id', $pp->id)->firstOrFail();
            $dashboard->state = 'sent';
            $dashboard->save();

            // Create attachment zip
            $reg = new FilesForRegistrator($pp);
            $reg->storeFiles();

            // Dispatch + mail after commit so we don't notify if DB fails
            DB::afterCommit(function () use ($pp, $dashboard) {
                $filePath = public_path('download/' . $pp->id . '/' . 'ProjectProposal-' . $pp->name . '.zip');

                $user = User::find($pp->dashboard->user_id);
                SendFinalToRegistrator::dispatch($user, $pp->dashboard, $filePath);

                $submitter = User::find($dashboard->user_id);
                $vice = $this->getViceHeadUser();
                Mail::to($vice->email)->send(new SentNotificationVice($submitter, $vice, $dashboard));
            });

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your proposal has been successfully registered as sent. Thank you!');
        });
    }

    private function handleGranted(Request $request, Carbon $submittedAt)
    {
        return DB::transaction(function () use ($request, $submittedAt) {

            $pp = ProjectProposal::findOrFail($request->id);

            $pp->update([
                'pp' => $this->mergePp($pp->pp ?? [], [
                    ...$request->only(['granted', 'cofinanced_promised', 'phd_promised', 'granted_comments']),
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'granted',
                ]),
                'status_stage1' => 'granted',
            ]);

            $dashboard = Dashboard::query()->where('request_id', $pp->id)->firstOrFail();
            $dashboard->state = 'granted';
            $dashboard->save();

            $this->comments_update($pp->id, $request->edit_comments, 'granted');

            $reg = new FilesForRegistrator($pp);
            $reg->storeDecisionLetter();

            DB::afterCommit(function () use ($pp, $dashboard) {
                $filePath = public_path('download/' . $pp->id . '/' . 'ProjectProposal-' . $pp->name . '.zip');

                $user = User::find($pp->dashboard->user_id);
                SendGrantToRegistrator::dispatch($user, $pp->dashboard, $filePath);

                $submitter = User::find($dashboard->user_id);
                $vice = $this->getViceHeadUser();
                Mail::to($vice->email)->send(new GrantNotificationVice($submitter, $vice, $dashboard));
            });

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your project proposal has been successfully registered as a granted project!');
        });
    }

    private function handleRejected(Request $request, Carbon $submittedAt)
    {
        return DB::transaction(function () use ($request, $submittedAt) {

            $pp = ProjectProposal::findOrFail($request->id);

            $pp->update([
                'pp' => $this->mergePp($pp->pp ?? [], [
                    ...$request->only(['rejected', 'rejected_comments']),
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'denied',
                ]),
            ]);

            $dashboard = Dashboard::query()->where('request_id', $pp->id)->firstOrFail();
            $dashboard->state = 'denied';
            $dashboard->save();

            $this->comments_update($pp->id, $request->edit_comments, 'rejected');

            $reg = new FilesForRegistrator($pp);
            $reg->storeDecisionLetter();

            DB::afterCommit(function () use ($pp) {
                $filePath = public_path('download/' . $pp->id . '/' . 'ProjectProposal-' . $pp->name . '.zip');

                $user = User::find($pp->dashboard->user_id);

                // NOTE: Your original code dispatches SendGrantToRegistrator here too.
                // If you have a separate "SendRejectedToRegistrator", swap it in.
                SendGrantToRegistrator::dispatch($user, $pp->dashboard, $filePath);
            });

            return redirect()->route('pp.show', 'my')
                ->with('success', 'Your project proposal has been registered as a denied project!');
        });
    }

    private function handleReview(Request $request, Carbon $submittedAt)
    {
        return DB::transaction(function () use ($request, $submittedAt) {

            $pp = ProjectProposal::findOrFail($request->id);

            $pp->update([
                'pp' => $this->mergePp($pp->pp ?? [], [
                    ...$request->only(['budget_project', 'budget_dsv', 'cofinancing_needed', 'budget_php']),
                    'submitted' => $submittedAt->toISOString(),
                    'status' => 'revised',
                ]),
            ]);

            $this->comments_update($pp->id, 'The budget has been revised by the financial administrator.', 'updated');

            return redirect()->back()
                ->withFragment('project_budget')
                ->with('success', 'Budget has been updated')
                ->withInput();
        });
    }

    /* -------------------------------------------------------------------------
     | Shared helpers
     * ---------------------------------------------------------------------- */

    private function buildPpPayload(Request $request, array $overrides = []): array
    {
        $base = $request->only([
            'title', 'objective', 'principal_investigator', 'principal_investigator_email',
            'co_investigator_name', 'co_investigator_email', 'co_investigator_type', 'co_investigator_role',
            'research_area', 'dsvcoordinating', 'other_coordination', 'eu', 'eu_wallenberg',
            'funding_organization', 'cofinancing', 'other_cofinancing', 'project_duration',
            'unit_head', 'program', 'decision_exp', 'start_date', 'submission_deadline',
            'budget_project', 'budget_dsv', 'budget_phd', 'currency', 'oh_cost',
            'cofinancing_needed',
        ]);

        // Normalize comment inputs:
        // - first-time forms post 'user_comments'
        // - edit/resume forms post 'comment'
        $initialUserComments = $request->input('user_comments');
        $newComment = $request->input('comment') ?? $request->input('edit_comments');

        // Only set pp.user_comments IF this is the first time / initial value.
        // If user_comments is present and comment is not, treat it as initial.
        if ($initialUserComments !== null && $newComment === null) {
            $base['user_comments'] = (string) $initialUserComments;
        }

        return $base + $overrides;
    }


    private function mergePp(array $existing, array $incoming): array
    {
        // Recursive safe merge so nested structures aren't blown away
        return array_replace_recursive($existing, $incoming);
    }

    private function dashboardBaseData(ProjectProposal $pp, Request $request, string $userId, int $createdTs, string $status): array
    {
        ['fo' => $foUserId, 'fo_eu' => $foEuUserId] = $this->getFoIds();

        $euYes = $this->truthy(data_get($pp->pp, 'eu'));
        $foId = $euYes ? $foEuUserId : $foUserId;

        return [
            'request_id' => $pp->id,
            'name'       => $request->title,
            'created'    => $createdTs,
            'status'     => $status,
            'type'       => 'projectproposal',
            'user_id'    => $userId,
            'fo_id'      => $foId,
            'vice_id'    => $this->getViceHeadUserId(),
        ];
    }

    private function upsertDashboardWithUnitHeads(ProjectProposal $pp, Request $request, array $dashboardData): Dashboard
    {
        $dashboard = Dashboard::updateOrCreate(
            ['request_id' => $pp->id],
            $dashboardData
        );

        $this->setUnitHeadsOnDashboard($dashboard, (array) $request->unit_head);

        return $dashboard;
    }

    private function setUnitHeadsOnDashboard(Dashboard $dashboard, array $unitHeads): void
    {
        $unitHeads = array_values(array_filter($unitHeads, fn ($v) => $v !== null && $v !== ''));

        $dashboard->unit_heads = $unitHeads;
        $dashboard->unit_head_approved = collect($unitHeads)
            ->mapWithKeys(fn ($uh) => [$uh => 0])
            ->toJson();

        $dashboard->multiple_heads = count($unitHeads) > 1;
        $dashboard->save();
    }

    private function getFoIds(): array
    {
        return Cache::remember('fo_ids', 600, function () {
            return [
                'fo'    => SettingsFo::query()->whereKey(1)->value('user_id'),
                'fo_eu' => SettingsFoEu::query()->whereKey(1)->value('user_id'),
            ];
        });
    }

    private function truthy($value): bool
    {
        // Handles 'yes', '1', 1, true, 'true', etc.
        if (is_string($value)) {
            $value = strtolower(trim($value));
            if (in_array($value, ['yes', 'y'], true)) return true;
            if (in_array($value, ['no', 'n'], true)) return false;
        }
        return filter_var($value, FILTER_VALIDATE_BOOL);
    }
    public function decision(Request $request)
    {
        $data = $request->validate([
            'id'       => ['required', 'string'],
            'decision' => ['required', 'in:approve,deny,return'],
            'comment'  => ['nullable', 'string', 'max:5000'],
        ]);

        $user = auth()->user();

        $dashboard = Dashboard::query()
            ->where('request_id', $data['id'])
            ->firstOrFail();

        $workflow = new WorkflowHandler($dashboard->workflow_id);
        $roleObj  = new DashboardRole($dashboard, $user);
        $actorRole = $roleObj->check(); // compute once

        // 1) Update comments (synchronous DB write)
        $statusMap = [
            'approve' => 'approved',
            'deny'    => 'denied',
            'return'  => 'returned',
        ];
        $this->comments_update($data['id'], $data['comment'] ?? null, $statusMap[$data['decision']]);

        // 2) Route by decision (no transaction)
        match ($data['decision']) {
            'approve' => $this->handleApprove($dashboard, $data['id'], $actorRole, $user, $workflow),
            'deny', 'return' => $this->handleDenyReturn($data['decision'], $actorRole, $workflow),
        };

        return redirect()->route('pp.show', ['slug' => 'awaiting']);
    }

    private function handleApprove(Dashboard $dashboard, string $requestId, string $actorRole, $user,
        WorkflowHandler $workflow
    ): void {
        switch ($actorRole) {
            case 'head':
                // Approve draft file
                (new ProposalFileReviewService($requestId))->approvePendingByType('draft');

                // Update unit_head_approved safely
                $unitHeadApproved = json_decode($dashboard->unit_head_approved, true);
                if (!is_array($unitHeadApproved)) {
                    $unitHeadApproved = [];
                }

                $alreadyApproved = (($unitHeadApproved[$user->id] ?? 0) === 1);

                if (!$alreadyApproved) {
                    $unitHeadApproved[$user->id] = 1;
                    $dashboard->unit_head_approved = json_encode($unitHeadApproved);
                    $dashboard->save();
                }

                if (!in_array(0, $unitHeadApproved, true)) {
                    $workflow->HeadApprove();
                }

                // Budget increments should be guarded to avoid double count
                if (!$alreadyApproved) {
                    $proposal = ProjectProposal::query()->findOrFail($dashboard->request_id);
                    $researchArea = $proposal->pp['research_area'] ?? null;

                    if ($researchArea) {
                        $budget = new Budget($proposal);
                        $budget->preapproved_increment($researchArea);
                        $budget->budget_increment($researchArea);
                        $budget->phd_increment($researchArea);
                        $budget->cost_increment($researchArea);
                    }
                }

                break;

            case 'fo':
                (new ProposalFileReviewService($requestId))->approvePendingByType('budget');
                $workflow->FOApprove();
                break;

            case 'vice_final':
                $workflow->FinalApprove();
                break;
        }
    }
    private function handleDenyReturn(string $decision, string $actorRole, WorkflowHandler $workflow): void
    {
        $map = [
            'deny' => [
                'head'       => 'HeadDeny',
                'fo'         => 'FODeny',
                'vice_final' => 'FinalDeny',
            ],
            'return' => [
                'head'       => 'HeadReturn',
                'fo'         => 'FOReturn',
                'vice_final' => 'FinalReturn',
            ],
        ];

        $method = $map[$decision][$actorRole] ?? null;
        if ($method) {
            $workflow->{$method}();
        }

        if (in_array($actorRole, ['head', 'fo'], true)) {
            (new ReCalcBudget())->scan();
        }
    }
    

    protected function validateRequest(Request $request)
    {
        $rules = [
            'title' => 'required',
            'objective' => 'required',
            'principal_investigator' => 'required',
            'user_comments' => ['nullable', 'string', 'max:5000'],
            'comment' => ['nullable', 'string', 'max:5000'],
            'edit_comments' => ['nullable', 'string', 'max:5000'],
            //'project_duration' => 'required|numeric|integer',
            //'oh_cost' => 'required|numeric|max:56'
        ];

        return $this->validate($request, $rules);
    }

    protected function comments_update(string $id, ?string $comment, ?string $type = null): int
    {
        /*if ($comment === null || trim($comment) === '') {
            return 0; // nothing to append
        }*/

        $proposal = ProjectProposal::query()->findOrFail($id);

        $pp = $proposal->pp ?? [];
        $existing = $pp['user_comments'] ?? '';

        $timestamp = now()->format('Y-m-d');
        $userName  = auth()->user()->name;
        $tag       = '**';

        $labels = [
            'edit'      => 'EDITED',
            'completed' => 'COMPLETED',
            'approved'  => 'APPROVED',
            'returned'  => 'RETURNED',
            'denied'    => 'DENIED',
            'resumed'   => 'RESUMED',
            'granted'   => 'GRANTED reported',
            'rejected'  => 'REJECTED reported',
            'updated'   => 'REVISED',
        ];

        $label = $labels[$type] ?? null;

        $commentsTag = $label
            ? "{$tag}  Proposal has been {$label} by {$userName}  {$timestamp}  {$tag}"
            : "{$tag}  {$userName}  {$timestamp}  {$tag}";

        $appendBlock = (string) \Illuminate\Support\Str::of('')
            ->newLine()
            ->append($commentsTag)
            ->newLine()
            ->append(trim($comment))
            ->newLine()
            ->newLine();

        // append-only
        $pp['user_comments'] = $existing . $appendBlock;
        $proposal->pp = $pp;
        $proposal->save();

        return 1;
    }

    protected function createAndStartWorkflow(Dashboard $dashboard)
    {
        $workflow = WorkflowStub::make(DSVProjectPWorkflow::class);

        $dashboard->forceFill([
            'workflow_id' => $workflow->id(),
        ])->save();

        $this->startAndSubmitWorkflow($workflow, $dashboard);

        return $workflow;
    }

    protected function startAndSubmitWorkflow($workflow, Dashboard $dashboard): void
    {
        $workflow->start($dashboard);
        $workflow->submit();
    }


    protected function resumeWorkflow(Dashboard $dashboard)
    {
        $state = $dashboard->state; // object

        $workflowClass = match (true) {
            $state instanceof HeadReturned  => \App\Workflows\ResumeFromUHProjectWorkflow::class,
            $state instanceof FoReturned    => \App\Workflows\ResumeFromFOProjectWorkflow::class,
            $state instanceof FinalReturned => \App\Workflows\ResumeFromFinalProjectWorkflow::class,
            default => null,
        };

        abort_unless($workflowClass, 400, 'Cannot resume workflow from current state.');

        $dashboard->state = RequestStates::SUBMITTED;

        $workflow = WorkflowStub::make($workflowClass);

        $dashboard->workflow_id = $workflow->id();
        $dashboard->save();

        $workflow->start($dashboard);
        $workflow->submit();

        return $workflow;
    }


    /***
     * Private functions
     */

    private function checkFiles($proposal): bool
    {
        return $proposal->hasAtLeastFilesOfType('draft', 1)
            && $proposal->hasAtLeastFilesOfType('budget', 1);
    }

    private function checkFileStatus($proposal): bool
    {
        $workflowHandler = new WorkflowHandler($proposal->dashboard->workflow_id);

        if (!$this->checkFiles($proposal)) {
            $workflowHandler->RemovedFile();
            return false;
        }

        $workflowHandler->UploadedFiles();

        $proposal->isTypeFullyApproved('budget')
            ? $workflowHandler->BudgetFileUnchanged()
            : $workflowHandler->BudgetFileChanged();

        $proposal->isTypeFullyApproved('draft')
            ? $workflowHandler->DraftFileUnchanged()
            : $workflowHandler->DraftFileChanged();

        return true;
    }


    private function getViceHeadUserId(): string
    {
        return DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
    }

    private function getViceHeadUser()
    {
        $viceUserID = DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
        return User::find($viceUserID);
    }

}
