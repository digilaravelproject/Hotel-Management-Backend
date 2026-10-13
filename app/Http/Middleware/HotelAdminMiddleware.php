<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HotelAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('hotel_admin');

        if (!$guard->check()) {
            return redirect()->route('landing')->with('error', 'Please log in to access the Hotel Admin panel.');
        }

        $hotelAdmin = $guard->user();

        // 1. Check Approval Status
        if ($hotelAdmin->approval_status === 'pending') {
            Auth::guard('hotel_admin')->logout();
            return redirect()->route('landing')->with('error', 'Your account is pending Super Admin approval.');
        } elseif ($hotelAdmin->approval_status === 'disapproved') {
            Auth::guard('hotel_admin')->logout();
            return redirect()->route('landing')->with('error', 'Your account has been disapproved by the Super Admin.');
        }

        // 2. Check Active Status
        if (!$hotelAdmin->status) {
            Auth::guard('hotel_admin')->logout();
            return redirect()->route('landing')->with('error', 'Your account is currently inactive.');
        }

        // 3. Check Pending Payment (Pay Later state): Allow login & dashboard/subscription page, block other features
        if ($hotelAdmin->payment_status !== 'paid') {
            // Allow dashboard and subscription/payment routes
            $allowedRoutes = [
                'hotel.dashboard',
                'hotel.subscribe',
                'hotel.logout',
            ];

            $currentRoute = $request->route()?->getName();

            if ($currentRoute && !in_array($currentRoute, $allowedRoutes)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Payment required. Please complete your subscription payment to access this feature.'
                    ], 403);
                }
                return redirect()->route('hotel.dashboard')
                                 ->with('error', 'Please complete your subscription payment to unlock panel features.');
            }
        }

        return $next($request);
    }
}
