<?php

namespace App\Workflows\Checks;

use App\Mail\RequestFilesUploadUser;
use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;
use Workflow\WorkflowStub;

class CheckUploadedFiles extends Activity
{
    protected $dashboard;
    protected $workflow;
    protected $proposal;

    public function execute(int $request): void
    {
        $this->loadDashboard($request);
        $users = $this->loadUsers();

        $this->workflow = WorkflowStub::load($this->dashboard->workflow_id);
        if($this->checkFiles($this->dashboard->request_id)) {
            //A Draft and a budgetfile exist
            $this->workflow->complete();
            return;
        }
        //Send email to user
        $this->sendNotification($users);
    }

    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
    }

    private function checkFiles(string $uid): bool
    {
        $this->proposal = ProjectProposal::find($uid);
        return $this->proposal->hasAtLeastFilesOfType('draft', 1)
            && $this->proposal->hasAtLeastFilesOfType('budget', 1);
    }

    private function sendNotification(array $users): void
    {
        $head = $users['heads']->first() ?? null;
        Mail::to($users['user']->email)->send(
            new RequestFilesUploadUser($users['user'], $head, $users['vice'], $this->dashboard));
    }

    private function loadUsers(): array
    {
        // Fetch all relevant users
        $userIds = [
            $this->dashboard->user_id,
            $this->dashboard->fo_id,
            $this->getViceHeadUserId(),
        ];

        // Fetch multiple heads
        $headIds = $this->getHeadUserIds();
        $userIds = array_merge($userIds, $headIds);

        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        return [
            'user' => $users[$this->dashboard->user_id],
            'fo' => $users[$this->dashboard->fo_id],
            'heads' => $users->only($headIds)->values(), // Multiple heads
            'vice' => $users[$this->getViceHeadUserId()],
        ];
    }

    private function getViceHeadUserId(): string
    {
        return DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
    }

    private function getHeadUserIds(): array
    {
        return $this->dashboard->unit_heads;
    }
}
