<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsFO
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Statamic\Facades\User::current();

        if (! $user) {
            abort(401);
        }

        if ($user->can('financial_officer')) {
            return $next($request);
        }

        abort(403);
        return redirect('/');
    }
}
