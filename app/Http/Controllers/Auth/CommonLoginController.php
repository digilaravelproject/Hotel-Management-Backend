<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CommonLoginController extends Controller
{
    /**
     * Show the unified login form.
     * If user is already authenticated in any guard, redirect to their role dashboard.
     */
    public function showLoginForm()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->hasRole('distributor')) {
                return redirect()->route('distributor.dashboard');
            }
            if ($user && $user->hasRole('super_admin')) {
                return redirect()->route('super-admin.dashboard');
            }
            return redirect()->route('distributor.dashboard');
        }

        if (Auth::guard('hotel_admin')->check()) {
            return redirect()->route('hotel.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle unified multi-role authentication.
     * Automatically identifies whether the user is Super Admin, Distributor, or Hotel Admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');
        $email = strtolower(trim($credentials['email']));
        $password = $credentials['password'];

        // ---------------------------------------------------------------------
        // 1. Check Super Admin Guard (SuperAdmin Model)
        // ---------------------------------------------------------------------
        $superAdmin = SuperAdmin::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($superAdmin && Hash::check($password, $superAdmin->password)) {
            // Check 2FA
            if ($superAdmin->google2fa_enabled) {
                session([
                    '2fa:user:id' => $superAdmin->id,
                    '2fa:user:guard' => 'super_admin',
                    '2fa:user:remember' => $remember,
                ]);
                return redirect()->route('2fa.verify');
            }

            Auth::guard('super_admin')->login($superAdmin, $remember);
            session()->put('2fa_verified', true);
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'role' => 'super_admin',
                    'redirect' => route('super-admin.dashboard'),
                ]);
            }

            return redirect()->intended(route('super-admin.dashboard'))
                             ->with('success', 'Welcome back, Super Admin!');
        }

        // ---------------------------------------------------------------------
        // 2. Check Web Guard (User Model: Distributor, etc.)
        // ---------------------------------------------------------------------
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($user && Hash::check($password, $user->password)) {
            if (!$user->status) {
                return back()->withErrors([
                    'email' => 'Your distributor account is currently deactivated. Please contact the administrator.',
                ])->onlyInput('email');
            }

            Auth::guard('web')->login($user, $remember);
            $request->session()->regenerate();

            // Direct to appropriate dashboard based on Spatie roles
            $redirectRoute = route('distributor.dashboard');
            if ($user->hasRole('super_admin')) {
                $redirectRoute = route('super-admin.dashboard');
            } elseif ($user->hasRole('distributor')) {
                $redirectRoute = route('distributor.dashboard');
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'role' => $user->roles->pluck('name')->first() ?? 'distributor',
                    'redirect' => $redirectRoute,
                ]);
            }

            return redirect()->intended($redirectRoute)
                             ->with('success', "Welcome back, {$user->name}!");
        }

        // ---------------------------------------------------------------------
        // 3. Check Hotel Admin Guard (HotelAdmin Model)
        // ---------------------------------------------------------------------
        $hotelAdmin = HotelAdmin::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($hotelAdmin && Hash::check($password, $hotelAdmin->password)) {
            // Check Approval Status
            if ($hotelAdmin->approval_status === 'pending') {
                return back()->withErrors([
                    'email' => 'Your hotel account is pending Super Admin approval. You will receive an email once approved.',
                ])->onlyInput('email');
            } elseif ($hotelAdmin->approval_status === 'disapproved') {
                return back()->withErrors([
                    'email' => 'Your hotel account has been disapproved by the Super Admin.',
                ])->onlyInput('email');
            }

            // Check Active Status
            if (!$hotelAdmin->status) {
                return back()->withErrors([
                    'email' => 'Your hotel account is currently inactive or suspended. Please contact support.',
                ])->onlyInput('email');
            }

            // Check 2FA
            if ($hotelAdmin->google2fa_enabled) {
                session([
                    '2fa:user:id' => $hotelAdmin->id,
                    '2fa:user:guard' => 'hotel_admin',
                    '2fa:user:remember' => $remember,
                ]);
                return redirect()->route('2fa.verify');
            }

            Auth::guard('hotel_admin')->login($hotelAdmin, $remember);
            session()->put('2fa_verified', true);
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'role' => 'hotel_admin',
                    'redirect' => route('hotel.dashboard'),
                ]);
            }

            return redirect()->intended(route('hotel.dashboard'))
                             ->with('success', 'Logged in successfully!');
        }

        // ---------------------------------------------------------------------
        // 4. Invalid Credentials
        // ---------------------------------------------------------------------
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Unified Logout for any authenticated guard.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('super_admin')->check()) {
            Auth::guard('super_admin')->logout();
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('hotel_admin')->check()) {
            Auth::guard('hotel_admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        }

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}

