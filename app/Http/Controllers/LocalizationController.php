<?php

namespace App\Http\Controllers;

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
                /*case($contains == false):
                    return back();
                    return redirect(url('') . $intended['path']);*/
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
}
