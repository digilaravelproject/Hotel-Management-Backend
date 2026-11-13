<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveDistributor
{
    /**
     * Block deactivated distributor / web panel accounts even if an existing session exists.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if (!$user || !$user->status) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been deactivated. Please contact Super Admin.',
                    ], 403);
                }

                return redirect()->route('distributor.login')
                    ->withErrors(['email' => 'Your account has been deactivated. Please contact Super Admin.']);
            }
        }

        return $next($request);
    }
}

