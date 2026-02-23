<?php

namespace App\Workflows\Notifications;

use App\Mail\NotifyRequestApproved;
use App\Mail\NotifyUserChangedState;
use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class StateUpdateNotification extends Activity
{
    protected Dashboard $dashboard;

    /***********************
     * @param $request
     *
     * Returned or Denied Notification
     */

    public function execute($request)
    {
        //Retrive request dashboard
        $id = $request;
        $this->dashboard = Dashboard::find($id);

        //Users
        $user = User::find($this->dashboard->user_id);
        $manager = User::find($this->dashboard->manager_id);
        $fo = User::find($this->dashboard->fo_id);
        //$head = User::find($this->dashboard->head_id);
        if(!is_null($headsIDs = $this->getHeadUserIds())) {
            $heads = User::whereIn('id', $headsIDs)->get();
        }

        $vice = User::find($this->dashboard->vice_id);

        //Notify user of changed Request State
        $state = (string)$this->dashboard->state;

        switch($state) {
            case('manager_returned'):
            case('manager_denied'):
                //Notify
                Mail::to($user->email)->send(new NotifyUserChangedState($user, $manager, $this->dashboard));
                break;
            case('fo_returned'):
            case('fo_denied'):
                //Notify
                Mail::to($user->email)->send(new NotifyUserChangedState($user, $fo, $this->dashboard));
                break;
            case('head_returned'):
            case('head_denied'):
                //Notify
                Mail::to($user->email)->send(new NotifyUserChangedState($user, $heads[0], $this->dashboard));
                break;
            case('vice_returned'):
            case('vice_denied'):
                //Notify
                Mail::to($user->email)->send(new NotifyUserChangedState($user, $vice, $this->dashboard));
                break;
            case('final_approved'):
                //Approved Request
                //Notify
                Mail::to($user->email)->send(new NotifyRequestApproved($user, $this->dashboard));
                break;
            case('fo_approved'):
                if($this->dashboard->type == 'travelrequest') {
                    Mail::to($user->email)->send(new NotifyRequestApproved($user, $this->dashboard));
                }
                break;
        }
    }

    private function getHeadUserIds()
    {
        return $this->dashboard->unit_heads;
    }
}
