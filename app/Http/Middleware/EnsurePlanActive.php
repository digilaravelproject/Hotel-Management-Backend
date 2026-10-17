<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePlanActive
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('hotel_admin')->check()) {
            return $next($request);
        }

        $hotel = Auth::guard('hotel_admin')->user();
        $hotel->loadMissing('plan');

        $hasPaidPlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());

        $allowedRoutes = [
            'hotel.dashboard',
            'hotel.package',
            'hotel.subscribe',
            'hotel.profile',
            'hotel.hotel-info',
            'hotel.logout',
        ];

        $currentRoute = $request->route()?->getName();

        if (!$hasPaidPlan && $currentRoute && !in_array($currentRoute, $allowedRoutes)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'An active subscription plan is required to access this feature.'
                ], 403);
            }
            return redirect()->route('hotel.dashboard')
                             ->with('error', 'Please subscribe to an active plan to access hotel features.');
        }

        return $next($request);
    }
}
