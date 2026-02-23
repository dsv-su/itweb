<?php

namespace App\Workflows\Notifications;

use App\Mail\NotifyRequestFO;
use App\Mail\NotifyRequestHead;
use App\Mail\NotifyRequestManager;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class NewRequestNotification extends Activity
{
    protected $dashboard;

    public function execute($recipent, $request)
    {
        //Retrive request dashboard
        //$id = $request[0];
        $id = $request;
        $this->dashboard = Dashboard::find($id);
        $user = User::find($this->dashboard->user_id);
        $manager = User::find($this->dashboard->manager_id);
        $fo = User::find($this->dashboard->fo_id);
        $head = User::find($this->dashboard->head_id);

        //Send email to recipent
        switch($recipent) {
            case('manager'):
                Mail::to($manager->email)->send(new NotifyRequestManager($user, $manager, $head, $this->dashboard));
                break;
            case('head'):
                Mail::to($head->email)->send(new NotifyRequestHead($user, $manager, $head, $this->dashboard));
                break;
            case('fo'):
                Mail::to($fo->email)->send(new NotifyRequestFO($user, $manager, $head, $this->dashboard));
                break;
        }
    }
}
