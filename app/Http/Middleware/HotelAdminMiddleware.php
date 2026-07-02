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

        // 3. Check Active Status
        if (!$hotelAdmin->status) {
            Auth::guard('hotel_admin')->logout();
            return redirect()->route('landing')->with('error', 'Your account is currently inactive.');
        }

        return $next($request);
    }
}
