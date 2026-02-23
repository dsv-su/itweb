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

    /*
    |--------------------------------------------------------------------------
    | Default LDAP Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the LDAP connections below you wish
    | to use as your default connection for all LDAP operations. Of
    | course you may add as many connections you'd like below.
    |
    */

    'default' => env('LDAP_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | LDAP Connections
    |--------------------------------------------------------------------------
    |
    | Below you may configure each LDAP connection your application requires
    | access to. Be sure to include a valid base DN - otherwise you may
    | not receive any results when performing LDAP search operations.
    |
    */

    'connections' => [

        'default' => [
            'hosts' => [$system_config['sukat']['host']],
            'username' => $system_config['sukat']['username'],
            'password' => $system_config['sukat']['password'],
            'port' => $system_config['sukat']['port'],
            'base_dn' => $system_config['sukat']['base_dn'],
            'timeout' => $system_config['sukat']['timeout'],
            'use_ssl' => env('LDAP_SSL', true),
            'use_tls' => env('LDAP_TLS', false),
            'use_sasl' => env('LDAP_SASL', false),
            'sasl_options' => [
                // 'mech' => 'GSSAPI',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Logging
    |--------------------------------------------------------------------------
    |
    | When LDAP logging is enabled, all LDAP search and authentication
    | operations are logged using the default application logging
    | driver. This can assist in debugging issues and more.
    |
    */

    'logging' => [
        'enabled' => env('LDAP_LOGGING', true),
        'channel' => env('LOG_CHANNEL', 'stack'),
        'level' => env('LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Cache
    |--------------------------------------------------------------------------
    |
    | LDAP caching enables the ability of caching search results using the
    | query builder. This is great for running expensive operations that
    | may take many seconds to complete, such as a pagination request.
    |
    */

    'cache' => [
        'enabled' => env('LDAP_CACHE', false),
        'driver' => env('CACHE_DRIVER', 'file'),
    ],

];
