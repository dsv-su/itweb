<?php

namespace App\Listeners;

use App\Events\UserCreatedSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Statamic\Facades\User;
use Statamic\Facades\UserGroup;

class AssignGroupToUser
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
    public function handle(UserCreatedSuccessful $event): void
    {
        $shibboleth_user = $event->user;

        //Retrive the user
        $user = User::findByEmail($shibboleth_user->email);

        // Load the user group by its handle
        $groupHandle = 'projektledare';
        $userGroup = UserGroup::find($groupHandle);

        // Add the user to the group
        $user->addToGroup($userGroup);

        // Save the user
        $user->save();

    }
}
