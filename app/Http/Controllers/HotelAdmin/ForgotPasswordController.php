<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password email entry form
     */
    public function showLinkRequestForm()
    {
        return view('hotel_admin.forgot_password');
    }

    /**
     * Generate OTP and send email
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim($request->email));
        $ipThrottleKey = 'otp-send:' . $request->ip();
        $emailThrottleKey = 'otp-send-email:' . sha1($email);

        if (RateLimiter::tooManyAttempts($ipThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($ipThrottleKey);
            return back()->with('error', "Too many OTP requests. Please wait {$seconds} seconds before trying again.");
        }
        if (RateLimiter::tooManyAttempts($emailThrottleKey, 3)) {
            $seconds = RateLimiter::availableIn($emailThrottleKey);
            return back()->with('error', "Please wait {$seconds} seconds before requesting another verification code.");
        }

        RateLimiter::hit($ipThrottleKey, 300);
        RateLimiter::hit($emailThrottleKey, 300);

        $hotelAdmin = HotelAdmin::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($hotelAdmin) {
            // Generate secure 6-digit OTP and store its cryptographic hash
            $otp = strval(random_int(100000, 999999));

            // Update database with hashed OTP and 15 minutes expiry
            $hotelAdmin->update([
                'otp_code' => Hash::make($otp),
                'otp_expires_at' => Carbon::now()->addMinutes(15)
            ]);

            // Send OTP email
            try {
                Mail::to($hotelAdmin->email)->send(new SendOtpMail($hotelAdmin, $otp));
            } catch (\Exception $e) {
                logger()->error('OTP Mail delivery failed: ' . $e->getMessage());
            }
        }

        // Put email in session for validation step
        session(['reset_email' => $email]);

        return redirect()->route('hotel.reset-password')
                         ->with('success', 'If the provided email is registered, a 6-digit verification code has been dispatched to it.');
    }

    /**
     * Show reset password / verification form
     */
    public function showResetForm()
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('hotel.forgot-password')
                             ->with('error', 'Please enter your email first.');
        }
        return view('hotel_admin.reset_password', compact('email'));
    }

    /**
     * Validate OTP and reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = strtolower(trim($request->email));
        $ipThrottleKey = 'otp-verify:' . $request->ip();
        $accountThrottleKey = 'otp-verify-acct:' . sha1($email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($ipThrottleKey, 5) || RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
            $seconds = max(RateLimiter::availableIn($ipThrottleKey), RateLimiter::availableIn($accountThrottleKey));
            return back()->withErrors(['otp_code' => "Too many incorrect verification attempts. Please wait {$seconds} seconds."])->withInput();
        }

        $hotelAdmin = HotelAdmin::whereRaw('LOWER(email) = ?', [$email])->first();

        $isExpired = !$hotelAdmin
            || !$hotelAdmin->otp_expires_at
            || Carbon::now()->isAfter(Carbon::parse($hotelAdmin->otp_expires_at));
        $isValidOtp = $hotelAdmin
            && $hotelAdmin->otp_code
            && !$isExpired
            && Hash::check((string) $request->otp_code, $hotelAdmin->otp_code);

        if (!$isValidOtp) {
            RateLimiter::hit($ipThrottleKey, 300);
            RateLimiter::hit($accountThrottleKey, 300);

            if ($hotelAdmin && RateLimiter::tooManyAttempts($accountThrottleKey, 5)) {
                $hotelAdmin->update(['otp_code' => null, 'otp_expires_at' => null]);
            }

            return back()->withErrors(['otp_code' => 'The verification code is invalid or has expired.'])
                         ->withInput();
        }

        RateLimiter::clear($ipThrottleKey);
        RateLimiter::clear($accountThrottleKey);

        // OTP is valid! Clear it and update password
        $hotelAdmin->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Clear session and rotate session ID + CSRF token
        session()->forget('reset_email');
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()->route('hotel.login')
                         ->with('success', 'Your password has been successfully reset. You can now login with your new password.');
    }
}
