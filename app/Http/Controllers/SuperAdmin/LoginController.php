<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::guard('super_admin')->validate($credentials)) {
            $user = \App\Models\SuperAdmin::where('email', $credentials['email'])->first();

            if ($user->google2fa_enabled) {
                session([
                    '2fa:user:id' => $user->id,
                    '2fa:user:guard' => 'super_admin',
                    '2fa:user:remember' => $request->boolean('remember'),
                ]);
                return redirect()->route('2fa.verify');
            }

            Auth::guard('super_admin')->login($user, $request->boolean('remember'));
            session()->put('2fa_verified', true);
            $request->session()->regenerate();
            return redirect()->intended(route('super-admin.dashboard'))
                             ->with('success', 'Welcome back, Super Admin!');
        }

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
