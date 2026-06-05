<?php

namespace App\Workflows\Notifications;

use App\Mail\NotifyRequestApproved;
use App\Mail\NotifyUserChangedState;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Workflow\Activity;

class StateUpdateNotification extends Activity
{
    protected Dashboard $dashboard;

    public function execute(int $dashboardId)
    {
        $this->dashboard = Dashboard::findOrFail($dashboardId);

        $user = User::findOrFail($this->dashboard->user_id);
        $state = (string) $this->dashboard->state;

        switch ($state) {
            case 'manager_returned':
            case 'manager_denied':
                Mail::to($user->email)->send(
                    new NotifyUserChangedState($user, User::findOrFail($this->dashboard->manager_id), $this->dashboard)
                );
                break;
            case 'fo_returned':
            case 'fo_denied':
                Mail::to($user->email)->send(
                    new NotifyUserChangedState($user, User::findOrFail($this->dashboard->fo_id), $this->dashboard)
                );
                break;
            case 'head_returned':
            case 'head_denied':
                Mail::to($user->email)->send(
                    new NotifyUserChangedState($user, $this->headReviewer(), $this->dashboard)
                );
                break;
            case 'vice_returned':
            case 'vice_denied':
            case 'final_returned':
            case 'final_denied':
                Mail::to($user->email)->send(
                    new NotifyUserChangedState($user, User::findOrFail($this->dashboard->vice_id), $this->dashboard)
                );
                break;
            case 'final_approved':
                Mail::to($user->email)->send(new NotifyRequestApproved($user, $this->dashboard));
                break;
            case 'fo_approved':
                if ($this->dashboard->type == 'travelrequest') {
                    Mail::to($user->email)->send(new NotifyRequestApproved($user, $this->dashboard));
                }
                break;
        }
    }

    private function headReviewer(): User
    {
        $headIds = $this->getHeadUserIds();

        $head = $headIds
            ? User::whereIn('id', $headIds)->first()
            : User::find($this->dashboard->head_id);

        if (! $head) {
            throw new RuntimeException("No head reviewer found for dashboard {$this->dashboard->id}.");
        }

        return $head;
    }

    private function getHeadUserIds(): array
    {
        if (is_array($this->dashboard->unit_heads)) {
            return array_filter($this->dashboard->unit_heads);
        }

        if ($this->dashboard->unit_heads) {
            return [$this->dashboard->unit_heads];
        }

        return [];
    }
}
