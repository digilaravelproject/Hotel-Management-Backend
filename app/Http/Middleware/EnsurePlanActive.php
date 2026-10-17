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

        $hasPaidPlan = $hotel->plan && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());

        if (!$hasPaidPlan && !$request->routeIs('hotel.package') && !$request->routeIs('hotel.subscribe')) {
            return redirect()->route('hotel.package');
        }

        return $next($request);
    }
}
