<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the Hotel Admin Dashboard.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        $hotel->loadMissing('plan');
        $plan = $hotel->plan;

        $deviceCount = \App\Models\ConnectedDevice::where('hotel_admin_id', $hotel->id)->count();
        $guestCount = \App\Models\Guest::where('hotel_id', $hotel->id)->whereNull('check_out_datetime')->count();
        
        $plans = [];
        if ($hotel->payment_status !== 'paid') {
            $plans = \App\Models\Plan::where('status', true)->orderBy('room_count', 'asc')->get();
        }

        return view('hotel_admin.dashboard', compact('hotel', 'plan', 'plans', 'deviceCount', 'guestCount'));
    }

    /**
     * Complete payment/subscription checkout from within the dashboard popup
     */
    public function subscribe(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $hotel = Auth::guard('hotel_admin')->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        $keyId = env('RAZORPAY_KEY_ID');
        $keySecret = env('RAZORPAY_KEY_SECRET');
        $isMockOrder = str_starts_with($request->razorpay_order_id, 'order_mock_');

        if ($keyId && $keySecret && !$isMockOrder) {
            try {
                $api = new \Razorpay\Api\Api($keyId, $keySecret);
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
            strtoupper(\Illuminate\Support\Str::random(4)),
            strtoupper(\Illuminate\Support\Str::random(4)),
            strtoupper(\Illuminate\Support\Str::random(4)),
            strtoupper(\Illuminate\Support\Str::random(4))
        );

        $paymentId = $request->razorpay_payment_id ?? 'pay_mock_' . \Illuminate\Support\Str::random(14);

        $hotel->update([
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'license_key' => $licenseKey,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $paymentId,
        ]);

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($hotel->email)->send(
                new \App\Mail\HotelRegisteredMail($hotel, 'Your existing password')
            );
        } catch (\Exception $e) {
            logger()->error('Mail delivery failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription activated successfully!',
        ]);
    }
}
