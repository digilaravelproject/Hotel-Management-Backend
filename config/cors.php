<?php

$mainDomain = trim((string) env('MAIN_DOMAIN', 'paxtvlauncher.com'));
$appDomain  = trim((string) env('APP_DOMAIN', 'app.paxtvlauncher.com'));
$isLocalEnv = in_array(env('APP_ENV', 'production'), ['local', 'testing'], true);

$allowedOrigins = array_filter([
    $mainDomain !== '' ? 'https://' . $mainDomain : null,
    $mainDomain !== '' ? 'https://www.' . $mainDomain : null,
    $appDomain !== '' ? 'https://' . $appDomain : null,
    $isLocalEnv ? 'http://localhost' : null,
    $isLocalEnv ? 'http://localhost:8000' : null,
    $isLocalEnv ? 'http://127.0.0.1' : null,
    $isLocalEnv ? 'http://127.0.0.1:8000' : null,
    ($isLocalEnv && $mainDomain !== '') ? 'http://' . $mainDomain : null,
    ($isLocalEnv && $appDomain !== '') ? 'http://' . $appDomain : null,
]);

$allowedOriginPatterns = [];
if ($mainDomain !== '') {
    $scheme = $isLocalEnv ? 'https?' : 'https';
    $allowedOriginPatterns[] = '#^' . $scheme . '://([a-zA-Z0-9-]+\.)*' . preg_quote($mainDomain, '#') . '(:[0-9]+)?$#';
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Enables seamless and secure cross-domain requests between marketing domain
    | (e.g. paxtvlauncher.com) and application subdomain (e.g. app.paxtvlauncher.com).
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

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => array_values($allowedOrigins),

    'allowed_origins_patterns' => $allowedOriginPatterns,

    'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'X-CSRF-TOKEN', 'X-Requested-With', 'Origin'],

    'exposed_headers' => ['X-CSRF-TOKEN'],

    'max_age' => 86400,

    'supports_credentials' => true,

];
