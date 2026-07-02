<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Mail\HotelRegisteredMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class RegistrationController extends Controller
{
    /**
     * Create Razorpay order (or mock order id if keys are not present in .env)
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        $keyId = env('RAZORPAY_KEY_ID');
        $keySecret = env('RAZORPAY_KEY_SECRET');

        if ($keyId && $keySecret) {
            try {
                $api = new Api($keyId, $keySecret);
                $order = $api->order->create([
                    'receipt' => 'rcpt_' . time(),
                    'amount' => intval($plan->price * 100), // amount in paise
                    'currency' => 'INR',
                ]);

                return response()->json([
                    'success' => true,
                    'is_mock' => false,
                    'key_id' => $keyId,
                    'order_id' => $order['id'],
                    'amount' => $order['amount'],
                    'plan_name' => $plan->name
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Razorpay Order Creation Failed: ' . $e->getMessage()
                ], 500);
            }
        }

        // Mock Order Fallback
        return response()->json([
            'success' => true,
            'is_mock' => true,
            'order_id' => 'order_mock_' . Str::random(12),
            'amount' => intval($plan->price * 100),
            'plan_name' => $plan->name
        ]);
    }

    /**
     * Complete hotel registration after payment validation
     */
    public function completeRegistration(Request $request)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'required|string|max:255',
            'room_count' => 'required|integer|min:1',
            'plan_id' => 'required|exists:plans,id',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $keyId = env('RAZORPAY_KEY_ID');
        $keySecret = env('RAZORPAY_KEY_SECRET');
        $isMockOrder = str_starts_with($request->razorpay_order_id, 'order_mock_');

        if ($keyId && $keySecret && !$isMockOrder) {
            // Verify payment signature on real transaction
            try {
                $api = new Api($keyId, $keySecret);
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ];
                $api->utility->verifyPaymentSignature($attributes);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment Verification Failed: ' . $e->getMessage()
                ], 400);
            }
        }

        // Generate License Key (XXXX-XXXX-XXXX-XXXX)
        $licenseKey = sprintf(
            "%s-%s-%s-%s",
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4))
        );

        $paymentId = $request->razorpay_payment_id ?? 'pay_mock_' . Str::random(14);

        // Create Hotel Admin record
        $hotelAdmin = HotelAdmin::create([
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'hotel_name' => $request->hotel_name,
            'hotel_location' => $request->hotel_location,
            'room_count' => $request->room_count,
            'plan_id' => $request->plan_id,
            'payment_status' => 'paid',
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $paymentId,
            'license_key' => $licenseKey,
            'approval_status' => 'pending',
            'status' => true,
        ]);

        // Send confirmation email
        try {
            Mail::to($hotelAdmin->email)->send(new HotelRegisteredMail($hotelAdmin, $request->password));
        } catch (\Exception $e) {
            // Log error but continue registration flow
            logger()->error('Mail delivery failed: ' . $e->getMessage());
        }

        // Save variables to session for the success landing screen display
        session([
            'success_hotel' => $hotelAdmin,
            'plain_password' => $request->password,
            'license_key' => $licenseKey,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('register.success')
        ]);
    }

    /**
     * Show registration success dashboard page
     */
    public function showSuccess()
    {
        $hotel = session('success_hotel');
        $plainPassword = session('plain_password');
        $licenseKey = session('license_key');

        if (!$hotel) {
            return redirect()->route('landing');
        }

        return view('landing.success', compact('hotel', 'plainPassword', 'licenseKey'));
    }
}
