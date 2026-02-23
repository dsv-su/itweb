<?php

namespace App\Http\Controllers;

use App\Services\Settings\AuthHandler;
use App\Services\Settings\ConfigurationHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SystemController extends Controller
{

    /**
     * Create the session, send the user away to the SU IDP
     * for authentication.
     */
    public function SUlogin()
    {
        $system = new AuthHandler();
        $system = $system->authorize();

        return Redirect::to('https://' . Request::server('SERVER_NAME')
            . ':' . Request::server('SERVER_PORT') . $system->global->login_route
            . '?target=' . action('\\' . __class__ . '@SUidpReturn'));
    }

    /**
     * Redirect user to intended route on returned successful login
     * from the SU IdP.
     */

    public function SUidpReturn()
    {
        Session::regenerate();
        return redirect()->intended('/')->cookie(
            'language', 'en', 0, null, null, false, false
        );
    }

    public function login()
    {
        return redirect('/shibboleth-authenticate');
    }

    public function start()
    {
        //Persist system configuration
        $init = new ConfigurationHandler();
        $init->system();
    }
}
