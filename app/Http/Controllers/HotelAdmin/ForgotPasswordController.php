<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email|exists:hotel_admins,email',
        ], [
            'email.exists' => 'This email address is not registered in our hotel database.'
        ]);

        $hotelAdmin = HotelAdmin::where('email', $request->email)->first();

        // Generate 6-digit OTP
        $otp = strval(mt_rand(100000, 999999));
        
        // Update database with OTP and 15 minutes expiry
        $hotelAdmin->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(15)
        ]);

        // Send OTP email
        try {
            Mail::to($hotelAdmin->email)->send(new SendOtpMail($hotelAdmin, $otp));
        } catch (\Exception $e) {
            logger()->error('OTP Mail delivery failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP email. Please try again later.');
        }

        // Put email in session for validation step
        session(['reset_email' => $request->email]);

        return redirect()->route('hotel.reset-password')
                         ->with('success', 'A 6-digit verification code has been sent to your email.');
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
            'email' => 'required|email|exists:hotel_admins,email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $hotelAdmin = HotelAdmin::where('email', $request->email)->first();

        // Check if OTP matches and is not expired
        if (!$hotelAdmin->otp_code || $hotelAdmin->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'The verification code is incorrect.'])
                         ->withInput();
        }

        if (Carbon::now()->isAfter(Carbon::parse($hotelAdmin->otp_expires_at))) {
            return back()->withErrors(['otp_code' => 'The verification code has expired. Please request a new one.'])
                         ->withInput();
        }

        // OTP is valid! Clear it and update password
        $hotelAdmin->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Clear session
        session()->forget('reset_email');

        return redirect()->route('hotel.login')
                         ->with('success', 'Your password has been successfully reset. You can now login with your new password.');
    }
}
