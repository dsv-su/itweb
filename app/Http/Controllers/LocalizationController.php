<?php

/*namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        $site = 'swe';
        $intended = url()->previous();
        $intended = parse_url($intended);
        $contains = Str::contains($intended['path'], 'swe');

        if($locale == 'sv') {
            App::setlocale('sv');
            session()->put('locale', 'sv');
            session(['localisation' => App::getLocale()]);
            switch ($intended['path']) {
                case('/'):
                    return redirect(url('') . $intended['path'] . $site);

                default:
                    return redirect(url('') . '/'. $site . $intended['path']);
            }
        }
        elseif($locale == 'en') {
            App::setlocale('en');
            session()->put('locale', 'sv');
            session(['localisation' => App::getLocale()]);
            switch ($intended['path']) {
                case('/swe'):
                    return redirect(url('') );
                case($contains == false):
                    return redirect(url('') . $intended['path']);
                default:
                    $intended['path'] = substr($intended['path'], 4);
                    return redirect(url('') . $intended['path']);
            }

        }
        return back();
    }
}*/
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        $supported = ['sv', 'en'];

        if (!in_array($locale, $supported)) {
            return back();
        }

        App::setLocale($locale);
        session()->put('locale', $locale);
        session(['localisation' => App::getLocale()]);

        $previous = url()->previous();
        $parsed = parse_url($previous);
        $path = $parsed['path'] ?? '/';

        // Normalize path
        $path = '/' . ltrim($path, '/');
        $isSwe = Str::startsWith($path, '/swe');

        if ($locale === 'sv') {
            // Add /swe if not present
            if (!$isSwe) {
                return redirect('/swe' . $path);
            }
            return redirect($path); // already Swedish
        }

        if ($locale === 'en') {
            // Remove /swe if present
            if ($isSwe) {
                $path = substr($path, 4); // remove "/swe"
                return redirect($path ?: '/');
            }
            return redirect($path); // already English
        }

        return back();
    }
}
