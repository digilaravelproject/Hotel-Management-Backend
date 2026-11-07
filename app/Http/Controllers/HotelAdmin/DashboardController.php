<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ConnectedDevice;
use App\Models\Guest;
use App\Models\Plan;
use App\Models\Amenity;
use App\Models\OttPlatform;
use App\Models\RoomInfo;

class DashboardController extends Controller
{
    /**
     * Show the Hotel Admin Dashboard.
     */
    public function index()
    {
        $hotel = Auth::guard('hotel_admin')->user();
        if (!$hotel) {
            return redirect()->route('login');
        }

        $hotel->loadMissing('plan');

        $hasActivePlan = $hotel->plan_id && $hotel->payment_status === 'paid' && ($hotel->expiry_date === null || $hotel->expiry_date->isFuture());
        if (!$hasActivePlan) {
            return redirect()->route('hotel.subscription');
        }

        $plan = $hotel->plan;

        // Device / TV Slot capacity metrics
        $deviceCount = ConnectedDevice::where('hotel_admin_id', $hotel->id)->count();
        $allowedLimit = (int) ($hotel->allowed_device_limit ?: ($hotel->room_count ?: ($plan ? $plan->room_count : 0)));
        $availableSlots = max(0, $allowedLimit - $deviceCount);
        $usagePercent = $allowedLimit > 0 ? min(100, round(($deviceCount / $allowedLimit) * 100)) : 0;

        // In-House & Monthly Guest metrics
        $guestCount = Guest::where('hotel_id', $hotel->id)->whereNull('check_out_datetime')->count();
        $totalCheckInsThisMonth = Guest::where('hotel_id', $hotel->id)
            ->whereMonth('check_in_datetime', now()->month)
            ->whereYear('check_in_datetime', now()->year)
            ->count();

        // 7-Day Guest Check-In Activity Trend
        $checkInLabels = [];
        $checkInCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $checkInLabels[] = $day->format('D, M d');
            $checkInCounts[] = Guest::where('hotel_id', $hotel->id)
                ->whereDate('check_in_datetime', $day->toDateString())
                ->count();
        }

        // Operational counts
        $amenitiesCount = Amenity::where('hotel_admin_id', $hotel->id)->count();
        $ottAppsCount = OttPlatform::where('status', 1)->count();
        $roomsConfigured = RoomInfo::where('hotel_admin_id', $hotel->id)->count();

        // Subscription countdown: strictly days remaining
        $daysRemaining = ($hotel->expiry_date && $hotel->expiry_date->isFuture())
            ? (int) ceil(now()->diffInHours($hotel->expiry_date) / 24)
            : 0;

        $plans = [];
        if ($hotel->payment_status !== 'paid') {
            $plans = Plan::where('status', true)
                ->where(function ($q) use ($hotel) {
                    $q->where('is_hidden', false);
                    if ($hotel && $hotel->plan_id) {
                        $q->orWhere('id', $hotel->plan_id);
                    }
                })
                ->orderBy('room_count', 'asc')
                ->get();
        }

        return view('hotel_admin.dashboard', compact(
            'hotel',
            'plan',
            'plans',
            'deviceCount',
            'allowedLimit',
            'availableSlots',
            'usagePercent',
            'guestCount',
            'totalCheckInsThisMonth',
            'checkInLabels',
            'checkInCounts',
            'amenitiesCount',
            'ottAppsCount',
            'roomsConfigured',
            'daysRemaining'
        ));
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
