<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;
        $guard = null;

        if (Auth::guard('super_admin')->check()) {
            $user = Auth::guard('super_admin')->user();
            $guard = 'super_admin';
        } elseif (Auth::guard('hotel_admin')->check()) {
            $user = Auth::guard('hotel_admin')->user();
            $guard = 'hotel_admin';
        }

        if ($user && $user->google2fa_enabled) {
            if (!session('2fa_verified', false)) {
                // Store pending 2FA state and logout current unverified session
                session([
                    '2fa:user:id' => $user->id,
                    '2fa:user:guard' => $guard,
                ]);

                Auth::guard($guard)->logout();

                return redirect()->route('2fa.verify');
            }
        }

        return $next($request);
    }
}
