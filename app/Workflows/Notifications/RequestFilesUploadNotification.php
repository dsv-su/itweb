<?php

namespace App\Workflows\Notifications;

use App\Mail\RequestFilesUploadUser;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class RequestFilesUploadNotification extends Activity
{
    protected Dashboard $dashboard;

    public function execute(int $request): void
    {
        $this->loadDashboard($request);
        $users = $this->loadUsers();

        // Send email based on recipient type
        $this->sendNotification($users);
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

    private function sendNotification(array $users): void
    {
        $head = $users['heads']->first() ?? null;
        Mail::to($users['user']->email)->send(
            new RequestFilesUploadUser($users['user'], $head, $users['vice'], $this->dashboard));
    }
}
