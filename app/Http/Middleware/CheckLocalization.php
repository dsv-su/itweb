<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Statamic\Facades\Site;
use Symfony\Component\HttpFoundation\Response;

class CheckLocalization
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

        // 1) Laravel locale (Blade translations, validation, etc.)
        App::setLocale($locale);

        // 2) Statamic site (Antlers content localization)
        $site = Site::get($locale)
            ?: Site::all()->first(fn ($site) => $site->shortLocale() === $locale || $site->lang() === $locale)
            ?: Site::default();

        if ($site) {
            Site::setCurrent($site->handle());
        }

        return $next($request);
    }
}
