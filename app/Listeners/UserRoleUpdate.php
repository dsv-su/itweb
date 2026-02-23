<?php

namespace App\Listeners;

use App\Mail\NotifyUserRoleUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserRoleUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //Disabled
        //$user = $event->user;
        //Mail::to($user->email)->send(new NotifyUserRoleUpdate($user));
    }
}
