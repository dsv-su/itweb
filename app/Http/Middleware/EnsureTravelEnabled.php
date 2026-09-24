<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTravelEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('travel.enabled')) {
            $lang = $request->route('lang');

            return redirect()->route(
                $lang ? 'travel-disabled.localized' : 'travel-disabled',
                $lang ? ['lang' => $lang] : [],
                303,
            );
        }

        return $next($request);
    }
}
