<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckLocalizaion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $current = url()->previous();
        $current = parse_url($current);
        $contains = Str::contains($current['path'], 'swe');

        if($contains) {
            App::setlocale('sv');
        } else {
            App::setlocale('en');
        }

        return $next($request);
    }
}
