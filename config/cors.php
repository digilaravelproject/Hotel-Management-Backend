<?php

$mainDomain = trim((string) env('MAIN_DOMAIN', 'paxtvlauncher.com'));
$appDomain  = trim((string) env('APP_DOMAIN', 'app.paxtvlauncher.com'));

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Enables seamless and secure cross-domain requests for TV launcher clients,
    | hotel portals, and external frontends across all origins.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'contact-us',
        'register/*',
        'hotel/register',
        'login',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [
        '#.*#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['*'],

    'max_age' => 86400,

    'supports_credentials' => false,

];
