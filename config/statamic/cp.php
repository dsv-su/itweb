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
    | Control Panel
    |--------------------------------------------------------------------------
    |
    | Whether the Control Panel should be enabled, and through what route.
    |
    */

    'enabled' => $system_config['statamic']['cp_enabled'],

    'route' => $system_config['statamic']['cp_route'],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Whether the Control Panel's authentication pages should be enabled,
    | and where users should be redirected in order to authenticate.
    |
    */

    'auth' => [
        'enabled' => true,
        'redirect_to' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Start Page
    |--------------------------------------------------------------------------
    |
    | When a user logs into the Control Panel, they will be taken here.
    | For example: "dashboard", "collections/pages", etc.
    |
    */

    'start_page' => 'dashboard',

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    |
    | Here you may define any number of dashboard widgets. You're free to
    | use the same widget multiple times in different configurations.
    |
    */

    'widgets' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Here you may define the default pagination size as well as the options
    | the user can select on any paginated listing in the Control Panel.
    |
    */

    'pagination_size' => 50,

    'pagination_size_options' => [10, 25, 50, 100, 500],

    /*
    |--------------------------------------------------------------------------
    | Links to Documentation
    |--------------------------------------------------------------------------
    |
    | Show contextual links to documentation throughout the Control Panel.
    |
    */

    'link_to_docs' => env('STATAMIC_LINK_TO_DOCS', true),

    /*
    |--------------------------------------------------------------------------
    | Support Link
    |--------------------------------------------------------------------------
    |
    | Set the location of the support link in the header.
    |
    */

    'support_url' => env('STATAMIC_SUPPORT_URL', 'https://statamic.com/support'),

    /*
    |--------------------------------------------------------------------------
    | White Labeling
    |--------------------------------------------------------------------------
    |
    | When in Pro Mode you may replace the Statamic name, logo, favicon,
    | and add your own CSS to the control panel to match your
    | company or client's brand.
    |
    */

    'custom_cms_name' => $system_config['statamic']['custom_cms_name'],

    'custom_logo_url' => env('STATAMIC_CUSTOM_LOGO_URL', null),

    'custom_dark_logo_url' => env('STATAMIC_CUSTOM_DARK_LOGO_URL', null),

    'custom_logo_text' => env('STATAMIC_CUSTOM_LOGO_TEXT', null),

    'custom_favicon_url' => env('STATAMIC_CUSTOM_FAVICON_URL', null),

    'custom_css_url' => env('STATAMIC_CUSTOM_CSS_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Thumbnails
    |--------------------------------------------------------------------------
    |
    | Here you may define additional CP asset thumbnail presets.
    |
    */

    'thumbnail_presets' => [
        // 'medium' => 800,
    ],

];
