<?php

namespace App\Workflows\Notifications;

use App\Mail\NotifyFONewProjectProposal;
use App\Mail\NotifyHeadResumeProjectProposal;
use App\Mail\NotifyViceNewProjectProposal;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class ResumeProjectProposalNotification extends Activity
{
    protected Dashboard $dashboard;

    public function execute(string $recipient, int $request): void
    {
        $this->loadDashboard($request);
        $users = $this->loadUsers();

        // Send email based on recipient type
        $this->sendNotification($recipient, $users);
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
        /*if($this->dashboard->multiple_heads) {
            return $this->dashboard->unit_heads;
        } else {
            return [$this->dashboard->head_id];

        }*/
    }

    private function sendNotification(string $recipient, array $users): void
    {
        switch ($recipient) {
            case 'head':
                foreach ($users['heads'] as $head) {
                    Mail::to($head->email)->send(
                        new NotifyHeadResumeProjectProposal($users['user'], $head, $users['vice'], $this->dashboard)
                    );
                }
                break;

            case 'vice':
                $head = $users['heads']->first() ?? null;

                if (!$head) {
                    throw new InvalidArgumentException("No head found to notify the vice.");
                }

                Mail::to($users['vice']->email)->send(
                    new NotifyViceNewProjectProposal($users['user'], $head, $users['vice'], $this->dashboard)
                );
                break;

            case 'fo':
                Mail::to($users['fo']->email)->send(
                    new NotifyFONewProjectProposal($users['user'], $users['fo'], $users['vice'], $this->dashboard)
                );
                break;

            default:
                throw new InvalidArgumentException("Invalid recipient type: $recipient");
        }
    }
}
