<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceStatamicElevatedSession
{
    public function handle(Request $request, Closure $next)
    {
        // Absolute path to the primary config file
        $primaryConfig = base_path('systemconfig/it.ini');
        $exampleConfig = base_path('systemconfig/it.ini.example');

        // Choose the real config if present, otherwise fallback to the example
        $configFile = file_exists($primaryConfig) ? $primaryConfig : $exampleConfig;

        // Parse the INI file with section support
        $system_config = parse_ini_file($configFile, true);

        // Validate parsing result
        if ($system_config === false) {
            throw new \RuntimeException(
                sprintf('Failed to parse configuration file: %s', $configFile)
            );
        }

        $enabled = filter_var($system_config['session']['elevated'], FILTER_VALIDATE_BOOL);

        if (
            $enabled
            && $request->is('cp', 'cp/*')
        ) {
            // Statamic checks this session key to determine elevation.
            $request->session()->put('statamic_elevated_session', now()->timestamp);
        }

        return $next($request);
    }
}
