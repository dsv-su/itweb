<?php

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

return [

    'pro' => $system_config['statamic']['pro_enabled'],

    'addons' => [
        //
    ],

];
