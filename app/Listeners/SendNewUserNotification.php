<?php

namespace App\Listeners;

use App\Mail\NewUser;
use App\Mail\SendUserConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewUserNotification
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
        $user = $event->user;
        Mail::to('ryan@dsv.su.se')->send(new NewUser($user));
        Mail::to('tuija@dsv.su.se')->send(new NewUser($user));
    }
}
