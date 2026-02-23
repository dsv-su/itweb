<?php

namespace App\Providers;

use App\Events\UserCreatedSuccessful;
use App\Listeners\AssignGroupToUser;
use App\Listeners\SendDSVInfoNotification;
use App\Listeners\SendNewUserNotification;
use App\Listeners\SendUserConfirmationEmail;
use App\Listeners\UserRoleUpdate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Statamic\Events\EntrySaved;
use Statamic\Events\UserSaved;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreatedSuccessful::class => [
            //SendUserConfirmationEmail::class,
            AssignGroupToUser::class,
            SendNewUserNotification::class,
        ],
        EntrySaved::class => [
            SendDSVInfoNotification::class,
        ],
        UserSaved::class => [
            UserRoleUpdate::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
