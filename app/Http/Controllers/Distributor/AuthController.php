<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show distributor login page.
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('distributor') || $user->hasRole('super_admin')) {
                return redirect()->route('distributor.dashboard');
            }
        }
        return view('distributor.login');
    }

    /**
     * Authenticate distributor.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // Verify account status
            if (!$user->status) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Your distributor account is currently deactivated. Please contact Super Admin.'])->onlyInput('email');
            }

            // Verify user has distributor or super_admin role
            if (!$user->hasRole('distributor') && !$user->hasRole('super_admin')) {
                Auth::guard('web')->logout();
                return back()->withErrors(['email' => 'Access denied. You do not have distributor privileges.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully as distributor.',
                    'user' => $user->load('roles'),
                ]);
            }

            return redirect()->intended(route('distributor.dashboard'))
                             ->with('success', "Welcome back, {$user->name}!");
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out distributor.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        }

        return redirect()->route('distributor.login')->with('success', 'You have been logged out.');
    }
}
