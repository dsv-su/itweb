<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Switchlocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->route('lang');

        if (is_string($lang)) {
            $lang = strtolower($lang);
        } else {
            $lang = null;
        }

        // Normalize/whitelist
        if ($lang === 'swe') {
            $locale = 'sv';
        } elseif ($lang === 'sv' || $lang === 'en') {
            $locale = $lang;
        } else {
            $locale = session('locale', config('app.fallback_locale', 'en'));
        }

        App::setLocale($locale);

        return $next($request);
    }
}
