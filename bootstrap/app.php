<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $appDomain = env('APP_DOMAIN', 'app.paxtvlauncher.com');

            $adminRoutes = Route::middleware('web');
            $hotelRoutes = Route::middleware('web');
            $distributorRoutes = Route::middleware('web');

            if (!app()->environment('local') && !empty($appDomain)) {
                $adminRoutes->domain($appDomain);
                $hotelRoutes->domain($appDomain);
                $distributorRoutes->domain($appDomain);
            }

            $adminRoutes->group(base_path('routes/admin.php'));
            $hotelRoutes->group(base_path('routes/hotel.php'));
            $distributorRoutes->group(base_path('routes/distributor.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        
        // SEC-10: Restrict trusted reverse proxies to Cloudflare CIDR blocks in production;
        // private subnets are trusted only when explicitly configured via TRUSTED_PROXIES or in local dev.
        $envProxies = env('TRUSTED_PROXIES');
        $cloudflareProxies = [
            '173.245.48.0/20',
            '103.21.244.0/22',
            '103.22.200.0/22',
            '103.31.4.0/22',
            '141.101.64.0/18',
            '108.162.192.0/18',
            '190.93.240.0/20',
            '188.114.96.0/20',
            '197.234.240.0/22',
            '198.41.128.0/17',
            '162.158.0.0/15',
            '104.16.0.0/13',
            '104.24.0.0/14',
            '172.64.0.0/13',
            '131.0.72.0/22',
            '127.0.0.1',
        ];
        if (in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)) {
            $cloudflareProxies = array_merge($cloudflareProxies, ['10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16']);
        }
        $trustedProxies = $envProxies ? array_filter(array_map('trim', explode(',', $envProxies))) : $cloudflareProxies;
        $middleware->trustProxies(at: $trustedProxies);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('distributor*')) {
                return route('distributor.login');
            }
            if ($request->is('hotel*')) {
                return route('hotel.login');
            }
            if ($request->is('super-admin*')) {
                return route('super-admin.login');
            }
            return route('landing');
        });

        // SEC-08: Eliminate CSRF exemption on contact-us. Only external payment callbacks remain exempt.
        $middleware->validateCsrfTokens(except: [
            'payment/*',
        ]);

        $middleware->alias([
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'hotel_admin' => \App\Http\Middleware\HotelAdminMiddleware::class,
            'tv_token' => \App\Http\Middleware\AuthenticateTvToken::class,
            '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'plan.active' => \App\Http\Middleware\EnsurePlanActive::class,
            'active_user' => \App\Http\Middleware\EnsureActiveDistributor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') && !config('app.debug')) {
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return null;
                }
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = $status < 500
                    ? $e->getMessage()
                    : 'An unexpected server error occurred. Please try again later.';
                return response()->json(['status' => false, 'message' => $message], $status);
            }
            return null;
        });
    })->create();
