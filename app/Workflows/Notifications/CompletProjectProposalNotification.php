<?php

namespace App\Workflows\Notifications;

use App\Mail\CompleteProjectProposal;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class CompletProjectProposalNotification extends Activity
{
    protected Dashboard $dashboard;

    public function execute(string $recipient, int $request): void
    {
        //Dashboard
        $this->loadDashboard($request);
        switch($recipient) {
            case 'user':
                $user = User::find($this->dashboard->user_id);
                $vice = $this->getViceHeadUser();
                //Email
                Mail::to($user->email)->send(new CompleteProjectProposal($user, $vice, $this->dashboard));
                break;
        }
    }
    private function loadDashboard(int $id): void
    {
        $this->dashboard = Dashboard::findOrFail($id);
    }

    private function getViceHeadUser()
    {
        $viceUserID = DB::table('role_user')
            ->where('role_id', 'vice_head')
            ->value('user_id');
        return User::find($viceUserID);
    }
}
