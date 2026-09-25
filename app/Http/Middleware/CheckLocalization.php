<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Statamic\Facades\Site;
use Statamic\Http\Controllers\FrontendController;
use Symfony\Component\HttpFoundation\Response;

class CheckLocalization
{
    public function handle(Request $request, Closure $next): Response
    {
        // The control panel manages its own user language.
        $cp = trim(config('statamic.cp.route', 'cp'), '/');
        if ($request->is($cp, $cp.'/*')) {
            return $next($request);
        }

        $normalize = static fn ($value) => match ($value) {
            'sv', 'swe' => 'sv',
            'en' => 'en',
            default => null,
        };

        $locale = $normalize($request->route('lang'))
            ?? $normalize($request->segment(1));

        // The English homepage uses this query parameter after an explicit switch.
        if ($request->is('/')) {
            $locale = $normalize($request->query('lang')) ?? $locale;
        }

        // CMS content uses the language of its URL; application routes use the
        // saved preference. Project proposals default to English on a fresh visit.
        if (!$locale && $request->route()?->getControllerClass() === FrontendController::class) {
            $locale = $normalize(Site::findByUrl($request->url())?->lang());
        }

        $defaultLocale = $request->is('projectproposals', 'projectproposals/*') ? 'en' : 'sv';
        $locale ??= $normalize($request->session()->get('locale')) ?? $defaultLocale;
        App::setLocale($locale);
        $request->session()->put(['locale' => $locale, 'localisation' => $locale]);

        $site = Site::all()->first(fn ($site) => $site->lang() === $locale || $site->shortLocale() === $locale);
        if ($site) {
            Site::setCurrent($site->handle());
        }

        return $next($request);
    }
}
