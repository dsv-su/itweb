<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        // Run AFTER Statamic's CP StartSession middleware so $request->session() is available.
        $router->pushMiddlewareToGroup('statamic.cp', \App\Http\Middleware\ForceStatamicElevatedSession::class);
    }
}
