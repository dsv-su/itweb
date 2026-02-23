<?php

namespace App\Http\Middleware;

use App\Models\Dashboard;
use Closure;
use Illuminate\Http\Request;
use Statamic\Auth\User;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserForDownload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /****
         * Ensure the right user downloads the request
         */

        $id = basename($request->getUri());
        $user = User::current();
        $dashboard = Dashboard::where('request_id',$id)->first();
        $valid_viewers = [$dashboard->user_id, $dashboard->manager_id, $dashboard->fo_id, $dashboard->head_id];

        if(in_array($user->id, $valid_viewers) or \Statamic\Auth\User::current()->can('financial_officer')) {
            return $next($request);
        }

        abort(401);
        return redirect('/');
    }
}
