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
     * Complete payment/subscription checkout from within the dashboard popup.
     */
    public function subscribe(
        \Illuminate\Http\Request $request,
        \App\Services\PaymentOrderService $paymentOrderService,
        \App\Services\SubscriptionService $subscriptionService
    ) {
        $hotel = Auth::guard('hotel_admin')->user();
        $validated = $request->validate([
            'plan_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('plans', 'id')->where(function ($q) use ($hotel) {
                    $q->where('status', true)->where(function ($inner) use ($hotel) {
                        $inner->where('is_hidden', false);
                        if ($hotel && $hotel->plan_id) {
                            $inner->orWhere('id', $hotel->plan_id);
                        }
                    });
                }),
            ],
            'duration_months' => 'nullable|integer|in:1,3,6,12,24,36',
            'tv_count' => 'nullable|integer|min:1|max:5000',
            'razorpay_order_id' => 'required|string|max:120',
            'razorpay_payment_id' => 'nullable|string|max:120',
            'razorpay_signature' => 'nullable|string|max:255',
        ]);

        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);
        $tvCount = max(1, (int) ($validated['tv_count'] ?? $hotel->room_count ?: ($plan->room_count ?: 1)));
        $durationMonths = (int) ($validated['duration_months'] ?? ($plan->billing_cycle === 'yearly' ? 12 : 1));
        $pricing = $paymentOrderService->calculateSubscriptionTotal($plan, $tvCount, $durationMonths);

        try {
            $gateway = \App\Services\PaymentManager::active();
            $verified = \Illuminate\Support\Facades\DB::transaction(function () use (
                $paymentOrderService,
                $subscriptionService,
                $gateway,
                $validated,
                $hotel,
                $plan,
                $tvCount,
                $durationMonths,
                $pricing
            ) {
                $payload = $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);
                $consumed = $paymentOrderService->consumeVerifiedPayment(
                    $gateway,
                    (string) $validated['razorpay_order_id'],
                    (string) ($validated['razorpay_payment_id'] ?? $validated['razorpay_order_id']),
                    $payload,
                    'hotel_subscription',
                    [
                        'hotel_admin_id' => $hotel->id,
                        'plan_id' => $plan->id,
                        'expected_amount' => $pricing['total_amount'],
                    ]
                );

                return $subscriptionService->activate($hotel, $plan, $durationMonths, [
                    'tv_count' => $tvCount,
                    'amount' => $consumed['amount'],
                    'payment_method' => 'online_gateway',
                    'gateway_code' => $gateway->getCode(),
                    'transaction_id' => $consumed['transaction_id'],
                    'payload' => $consumed['payload'],
                    'skip_payment_tx_record' => true,
                    'notes' => "Dashboard online {$gateway->getName()} payment: {$consumed['transaction_id']}",
                ]);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e instanceof \RuntimeException ? $e->getMessage() : 'Payment verification failed. Please retry or contact support.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription activated successfully!',
            'new_expiry' => $verified['new_expiry']->format('Y-m-d'),
        ]);
    }
}
