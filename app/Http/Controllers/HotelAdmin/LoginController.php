<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Show the hotel admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('hotel_admin')->check()) {
            $user = Auth::guard('hotel_admin')->user();
            $hasActivePlan = $user->plan_id 
                && $user->payment_status === 'paid' 
                && ($user->expiry_date === null || $user->expiry_date->isFuture());
            return redirect()->route($hasActivePlan ? 'hotel.dashboard' : 'hotel.subscription');
        }
        return view('hotel_admin.login');
    }

    /**
     * Handle hotel admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = strtolower(trim($credentials['email']));
        $throttleKey = 'hotel-login:' . $email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        if (Auth::guard('hotel_admin')->validate(['email' => $email, 'password' => $credentials['password']])) {
            $hotelAdmin = \App\Models\HotelAdmin::where('email', $email)->first();

            if ($hotelAdmin->approval_status === 'pending') {
                return back()->withErrors(['email' => 'Your account is pending Super Admin approval. You will receive an email once approved.']);
            } elseif ($hotelAdmin->approval_status === 'disapproved') {
                return back()->withErrors(['email' => 'Your account has been disapproved by the Super Admin.']);
            }

            if (!$hotelAdmin->status) {
                return back()->withErrors(['email' => 'Your account is currently inactive/suspended.']);
            }

            RateLimiter::clear($throttleKey);

            if ($hotelAdmin->google2fa_enabled) {
                session()->forget(["2fa_verified:hotel_admin:{$hotelAdmin->id}", '2fa_verified']);
                $request->session()->regenerate();
                session([
                    '2fa:user:id' => $hotelAdmin->id,
                    '2fa:user:guard' => 'hotel_admin',
                    '2fa:user:remember' => $request->boolean('remember'),
                ]);
                return redirect()->route('2fa.verify');
            }

            Auth::guard('hotel_admin')->login($hotelAdmin, $request->boolean('remember'));
            session()->forget('2fa_verified');
            session()->put("2fa_verified:hotel_admin:{$hotelAdmin->id}", true);
            $request->session()->regenerate();

            $hasActivePlan = $hotelAdmin->plan_id 
                && $hotelAdmin->payment_status === 'paid' 
                && ($hotelAdmin->expiry_date === null || $hotelAdmin->expiry_date->isFuture());

            return redirect()->intended($hasActivePlan ? route('hotel.dashboard') : route('hotel.subscription'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the hotel admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('hotel_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')
                         ->with('success', 'Logged out successfully.');
    }
}
