<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the hotel admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('hotel_admin')->check()) {
            return redirect()->route('hotel.dashboard');
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

        if (Auth::guard('hotel_admin')->attempt($credentials)) {
            $hotelAdmin = Auth::guard('hotel_admin')->user();


            if ($hotelAdmin->approval_status === 'pending') {
                Auth::guard('hotel_admin')->logout();
                return back()->withErrors(['email' => 'Your account is pending Super Admin approval. You will receive an email once approved.']);
            } elseif ($hotelAdmin->approval_status === 'disapproved') {
                Auth::guard('hotel_admin')->logout();
                return back()->withErrors(['email' => 'Your account has been disapproved by the Super Admin.']);
            }

            if (!$hotelAdmin->status) {
                Auth::guard('hotel_admin')->logout();
                return back()->withErrors(['email' => 'Your account is currently inactive/suspended.']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('hotel.dashboard'))
                             ->with('success', 'Logged in successfully!');
        }

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
