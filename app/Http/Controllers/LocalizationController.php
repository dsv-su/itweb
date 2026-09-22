<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Statamic\Facades\Site;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        $locale = $locale === 'swe' ? 'sv' : $locale;
        $supported = ['sv', 'en'];

        if (!in_array($locale, $supported)) {
            return back();
        }

        // 1) Laravel locale
        App::setLocale($locale);
        session()->put('locale', $locale);
        session(['localisation' => App::getLocale()]);

        // 2) Statamic site (Antlers)
        $site = Site::get($locale)
            ?: Site::all()->first(fn ($site) => $site->shortLocale() === $locale || $site->lang() === $locale)
            ?: Site::default();

        if ($site) {
            Site::setCurrent($site->handle());
        }
        $previous = url()->previous();
        $parsed = parse_url($previous);
        $path = $parsed['path'] ?? '/';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';

        // Normalize path
        $path = '/' . ltrim($path, '/');
        $originalPath = $path;
        $path = preg_replace('#^/(?:swe|sv|en)(?=/|$)#', '', $path) ?: '/';

        // Carry the homepage selection through the redirect, even if the
        // following request has not received the updated session yet.
        if (rtrim($path, '/') === '') {
            parse_str($parsed['query'] ?? '', $parameters);
            unset($parameters['lang']);

            if ($locale === 'en') {
                $parameters['lang'] = 'en';
            }

            $query = $parameters ? '?' . http_build_query($parameters) : '';

            return redirect(($locale === 'en' ? '/' : '/swe') . $query);
        }

        // Application pages without a localized route (settings, projects, etc.)
        // keep their URL and use the session preference instead.
        $target = $locale === 'sv' ? '/swe' . $path : $path;
        $routes = app('router')->getRoutes();
        $originalRoute = $routes->match(\Illuminate\Http\Request::create(url($originalPath)));
        if ($originalRoute->getControllerClass() !== \Statamic\Http\Controllers\FrontendController::class) {
            foreach (array_unique([$target, $path, '/' . ($locale === 'sv' ? 'swe' : 'en') . $path]) as $candidate) {
                try {
                    $candidateRoute = $routes->match(\Illuminate\Http\Request::create(url($candidate)));
                    if ($candidateRoute->getControllerClass() === $originalRoute->getControllerClass()) {
                        $target = $candidate;
                        break;
                    }
                } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception) {
                    // Try the next URL when this language has no matching route.
                }
            }
        }

        return redirect($target . $query);
    }
}
