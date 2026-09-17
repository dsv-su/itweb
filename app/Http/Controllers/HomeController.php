<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\FrontendController;

class HomeController extends FrontendController
{
    public function index(Request $request)
    {
        if ($request->query('lang', $request->session()->get('locale')) === 'en') {
            session(['locale' => 'en', 'localisation' => 'en']);
            app()->setLocale('en');

            return parent::index($request);
        }

        session(['locale' => 'sv', 'localisation' => 'sv']);
        app()->setLocale('sv');

        $query = $request->getQueryString();

        return redirect('/swe' . ($query ? '?' . $query : ''));
    }
}
