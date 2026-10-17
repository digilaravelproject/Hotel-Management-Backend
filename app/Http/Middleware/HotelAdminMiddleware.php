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

        // 3. Check Subscription & Payment Status: If no active plan, unpaid, or expired, lock panel and force to Package / Payment page
        $hasActivePlan = $hotelAdmin->plan_id 
            && $hotelAdmin->payment_status === 'paid' 
            && ($hotelAdmin->expiry_date === null || $hotelAdmin->expiry_date->isFuture());

        if (!$hasActivePlan) {
            // Only allow package/subscription page, payment submit, and sign out
            $allowedRoutes = [
                'hotel.package',
                'hotel.subscription',
                'hotel.subscription.order',
                'hotel.subscription.purchase',
                'hotel.subscribe',
                'hotel.logout',
            ];

            $currentRoute = $request->route()?->getName();

            if ($currentRoute && !in_array($currentRoute, $allowedRoutes)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Payment required. An active subscription is required to access hotel features.'
                    ], 403);
                }
                return redirect()->route('hotel.package')
                                 ->with('error', 'Payment required. Please select a plan and complete payment to unlock hotel panel features.');
            }
        }

        return $next($request);
    }
}
