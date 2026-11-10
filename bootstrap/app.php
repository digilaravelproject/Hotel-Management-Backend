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

        $middleware->validateCsrfTokens(except: [
            'payment/*',
            'contact-us',
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
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
