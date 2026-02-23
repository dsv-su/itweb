<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'dsv' => \App\Http\Middleware\DSVStaffEntitlement::class,
            'review' => \App\Http\Middleware\EnsureUserForReview::class,
            'show' => \App\Http\Middleware\EnsureUserForShow::class,
            'fo' => \App\Http\Middleware\EnsureUserIsFO::class,
            'checklang' => \App\Http\Middleware\CheckLocalizaion::class,
            'locale' => \App\Http\Middleware\Switchlocale::class,
            'download' => \App\Http\Middleware\EnsureUserForDownload::class,
            'vicehead' => \App\Http\Middleware\EnsureUserIsVice::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
