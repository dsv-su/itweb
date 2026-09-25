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
        \Illuminate\Support\Facades\Bus::pipeThrough([\App\Bus\Middleware\SkipEndedProposalJobs::class]);

        // Run AFTER Statamic's CP StartSession middleware so $request->session() is available.
        $router->pushMiddlewareToGroup('statamic.cp', \App\Http\Middleware\ForceStatamicElevatedSession::class);
    }
}
