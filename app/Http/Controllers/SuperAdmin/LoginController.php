<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Show the super admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }
        return view('super_admin.login');
    }

    /**
     * Handle super admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = strtolower(trim($credentials['email']));
        $throttleKey = 'super-admin-login:' . $email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        if (Auth::guard('super_admin')->validate(['email' => $email, 'password' => $credentials['password']])) {
            $user = \App\Models\SuperAdmin::where('email', $email)->first();
            RateLimiter::clear($throttleKey);

            if ($user->google2fa_enabled) {
                session()->forget(["2fa_verified:super_admin:{$user->id}", '2fa_verified']);
                $request->session()->regenerate();
                session([
                    '2fa:user:id' => $user->id,
                    '2fa:user:guard' => 'super_admin',
                    '2fa:user:remember' => $request->boolean('remember'),
                ]);
                return redirect()->route('2fa.verify');
            }

            Auth::guard('super_admin')->login($user, $request->boolean('remember'));
            session()->forget('2fa_verified');
            session()->put("2fa_verified:super_admin:{$user->id}", true);
            $request->session()->regenerate();
            return redirect()->intended(route('super-admin.dashboard'))
                             ->with('success', 'Welcome back, Super Admin!');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the super admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login')
                         ->with('success', 'Logged out successfully.');
    }
}
