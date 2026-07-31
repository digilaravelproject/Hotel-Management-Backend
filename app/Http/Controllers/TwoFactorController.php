<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Get currently authenticated admin user (either super_admin or hotel_admin).
     */
    protected function getUser()
    {
        $route = request()->route();
        $routeName = $route ? $route->getName() : '';

        if ($routeName && str_starts_with($routeName, 'hotel.') && Auth::guard('hotel_admin')->check()) {
            return Auth::guard('hotel_admin')->user();
        }

        if ($routeName && str_starts_with($routeName, 'super-admin.') && Auth::guard('super_admin')->check()) {
            return Auth::guard('super_admin')->user();
        }

        // Fallbacks
        if (Auth::guard('hotel_admin')->check()) {
            return Auth::guard('hotel_admin')->user();
        }
        if (Auth::guard('super_admin')->check()) {
            return Auth::guard('super_admin')->user();
        }
        return null;
    }

    /**
     * Generate 2FA secret and QR code for setup.
     */
    public function generate(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $secret = $this->google2fa->generateSecretKey();
        $appName = config('app.name', 'HotelTV');
        $email = $user->email;

        // Generate QR code inline SVG
        $qrCodeUrl = $this->google2fa->getQRCodeUrl($appName, $email, $secret);
        
        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        // Store temporary secret in session until confirmed
        session(['2fa_setup_secret' => $secret]);

        return response()->json([
            'secret' => $secret,
            'qr_code_svg' => base64_encode($qrCodeSvg),
        ]);
    }

    /**
     * Confirm and enable 2FA with 6-digit TOTP code.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = $this->getUser();
        $secret = session('2fa_setup_secret');

        if (!$user || !$secret) {
            return response()->json(['message' => 'Setup session expired. Please try again.'], 422);
        }

        $valid = $this->google2fa->verifyKey($secret, $request->one_time_password);

        if (!$valid) {
            return response()->json(['message' => 'Invalid OTP code. Please check your authenticator app.'], 422);
        }

        // Generate 8 emergency recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10) . '-' . Str::random(10);
        }

        $user->google2fa_secret = $secret;
        $user->google2fa_enabled = true;
        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->save();

        session()->forget('2fa_setup_secret');

        return response()->json([
            'message' => 'Two-Factor Authentication enabled successfully!',
            'recovery_codes' => $recoveryCodes
        ]);
    }

    /**
     * Disable 2FA (requires current password + live 6-digit TOTP code).
     */
    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'one_time_password' => 'required|digits:6',
        ]);

        $user = $this->getUser();
        if (!$user || !$user->google2fa_enabled) {
            return response()->json(['message' => '2FA is not enabled.'], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'The provided password does not match.'], 422);
        }

        $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);
        if (!$valid) {
            return response()->json(['message' => 'Invalid OTP code.'], 422);
        }

        $user->google2fa_secret = null;
        $user->google2fa_enabled = false;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return response()->json(['message' => 'Two-Factor Authentication disabled successfully.']);
    }

    /**
     * Show 2FA Challenge Login Verification Screen.
     */
    public function showVerifyForm()
    {
        if (!session()->has('2fa:user:id') || !session()->has('2fa:user:guard')) {
            return redirect()->route('super-admin.login');
        }

        return view('auth.2fa_verify');
    }

    /**
     * Verify 2FA challenge code during login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        if (!session()->has('2fa:user:id') || !session()->has('2fa:user:guard')) {
            return redirect()->route('super-admin.login')->withErrors(['otp' => 'Session expired. Please log in again.']);
        }

        $userId = session('2fa:user:id');
        $guard = session('2fa:user:guard');
        $remember = session('2fa:user:remember', false);

        // Rate Limiting Key
        $throttleKey = '2fa-verify:' . $userId . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['otp' => "Too many verification attempts. Please try again in {$seconds} seconds."]);
        }

        $model = $guard === 'super_admin' ? \App\Models\SuperAdmin::class : \App\Models\HotelAdmin::class;
        $user = $model::find($userId);

        if (!$user || !$user->google2fa_enabled) {
            session()->forget(['2fa:user:id', '2fa:user:guard', '2fa:user:remember']);
            return redirect()->route('super-admin.login');
        }

        $otpInput = trim($request->one_time_password);
        $verified = false;

        // 1. Try TOTP code check
        if (strlen($otpInput) === 6 && ctype_digit($otpInput)) {
            $verified = $this->google2fa->verifyKey($user->google2fa_secret, $otpInput);
        }

        // 2. Fallback check for emergency recovery codes
        if (!$verified && is_array($user->two_factor_recovery_codes)) {
            $recoveryCodes = $user->two_factor_recovery_codes;
            if (($key = array_search($otpInput, $recoveryCodes)) !== false) {
                $verified = true;
                unset($recoveryCodes[$key]);
                $user->two_factor_recovery_codes = array_values($recoveryCodes);
                $user->save();
            }
        }

        if (!$verified) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['otp' => 'Invalid verification code or recovery code.']);
        }

        RateLimiter::clear($throttleKey);

        // Complete Authentication
        Auth::guard($guard)->login($user, $remember);
        session()->put('2fa_verified', true);
        session()->forget(['2fa:user:id', '2fa:user:guard', '2fa:user:remember']);

        if ($guard === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        return redirect()->route('hotel.dashboard');
    }
}
