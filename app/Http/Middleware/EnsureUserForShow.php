<?php

namespace App\Http\Middleware;

use App\Models\Dashboard;
use Closure;
use Illuminate\Http\Request;
use Statamic\Auth\User;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserForShow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /****
         * Ensure the right reviewer reviews the request
         */

        $id = basename($request->getUri());
        $user = User::current();
        $dashboard = Dashboard::find($id);
        $valid_viewers = [$dashboard->user_id, $dashboard->manager_id, $dashboard->fo_id, $dashboard->head_id];

        if(in_array($user->id, $valid_viewers)) {
            return $next($request);
        }

        abort(401);
        return redirect('/');
    }
}
