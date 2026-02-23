<?php

namespace App\Workflows\Notifications;

use App\Mail\NotifyFONewProjectProposal;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class NewPPtoFinance extends Activity
{
    protected Dashboard $dashboard;
    public function execute(int $dashboardId): void
    {
        $this->loadDashboard($dashboardId);
        $users = $this->loadUsers();

        $ids = DB::table('group_user')
            ->where('group_id', 'ekonomi')
            ->pluck('user_id');

        $recipients = User::whereIn('id', $ids)->get();

        foreach ($recipients as $recipient) {
            $this->sendNotification($recipient, $users);
        }
    }

    private function sendNotification($recipient, array $users): void
    {
        Mail::to($recipient->email)->send(
            new NotifyFONewProjectProposal($users['user'], $recipient, $users['vice'], $this->dashboard)
        );
    }

    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
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
