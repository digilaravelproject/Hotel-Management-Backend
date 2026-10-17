<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Models\ConnectedDevice;
use App\Models\PaymentTransaction;
use App\Services\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DistributorController extends Controller
{
    /**
     * Inject reusable service layer.
     */
    public function __construct(
        protected \App\Services\DashboardService $dashboardService,
        protected \App\Services\HotelService $hotelService
    ) {}

    /**
     * Distributor dashboard with key metrics and recent records.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $data = $this->dashboardService->getDistributorDashboardData($user->id);

        $totalHotels = $data['metrics']['total_hotels'];
        $activeHotels = $data['metrics']['active_hotels'];
        $totalSalesCount = $data['metrics']['sales_count'];
        $totalRevenue = $data['metrics']['total_revenue'];
        $recentHotels = $data['recentHotels'];
        $recentSales = $data['recentTransactions'];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'metrics' => [
                    'total_hotels' => $totalHotels,
                    'active_hotels' => $activeHotels,
                    'total_sales' => $totalSalesCount,
                    'total_revenue' => $totalRevenue,
                ],
                'recent_hotels' => $recentHotels,
                'recent_sales' => $recentSales,
            ]);
        }

        return view('distributor.dashboard', compact(
            'totalHotels',
            'activeHotels',
            'totalSalesCount',
            'totalRevenue',
            'recentHotels',
            'recentSales'
        ));
    }

    /**
     * List all hotels onboarded by this distributor.
     */
    public function hotels(Request $request)
    {
        $user = auth()->user();
        $query = $user->hotels()->with('plan')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('hotel_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('license_key', 'like', "%{$search}%");
            });
        }

        $hotels = $query->paginate(12)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $hotels,
            ]);
        }

        return view('distributor.hotels.index', compact('hotels'));
    }

    /**
     * Show form to onboard a new hotel client.
     */
    public function createHotel()
    {
        $plans = Plan::where('status', 1)->get();
        return view('distributor.hotels.create', compact('plans'));
    }

    /**
     * Register a new hotel under this distributor using HotelService.
     */
    public function storeHotel(\App\Http\Requests\StoreHotelRequest $request)
    {
        $user = auth()->user();
        $hotel = $this->hotelService->createHotelForDistributor($user->id, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Hotel '{$hotel->hotel_name}' onboarded successfully.",
                'data' => $hotel->load('plan'),
            ], 201);
        }

        return redirect()->route('distributor.hotels.index')
                         ->with('success', "Hotel '{$hotel->hotel_name}' registered successfully! License Key: {$hotel->license_key}");
    }

    /**
     * List all sales and packages sold by this distributor.
     */
    public function sales(Request $request)
    {
        $user = auth()->user();
        $query = $user->distributorSales()->with(['hotel', 'plan'])->latest();

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->input('hotel_id'));
        }

        $sales = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $sales,
            ]);
        }

        return view('distributor.sales.index', compact('sales'));
    }

    /**
     * Show form to sell/assign a package to an onboarded hotel.
     */
    public function createSale(Request $request)
    {
        $user = auth()->user();
        $hotels = $user->hotels()->orderBy('hotel_name')->get();
        $plans = Plan::where('status', 1)->get();
        $selectedHotelId = $request->query('hotel_id');

        return view('distributor.sales.create', compact('hotels', 'plans', 'selectedHotelId'));
    }

    /**
     * Create a payment gateway order (AJAX) for online gateway sales.
     * Returns the gateway order id + key needed to open Razorpay Checkout on the frontend.
     */
    public function createSaleOrder(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotel_admins,id',
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'required|integer|in:1,3,6,12',
        ]);

        // Security check: hotel must belong to the authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::findOrFail($validated['plan_id']);

        // Amount is always computed server-side to prevent tampering
        $amount = round((float) $plan->price * (int) $validated['duration_months'], 2);

        try {
            $gateway = PaymentManager::active();

            if (!$gateway->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No online payment gateway is currently enabled. Please contact support.',
                ], 422);
            }

            $order = $gateway->createOrder($amount, 'DSALE-' . strtoupper(Str::random(10)), [
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'gateway' => $gateway->getCode(),
            'key_id' => $order['key_id'] ?? null,
            'order_id' => $order['order_id'],
            'amount' => $order['amount'] ?? $amount,
            'currency' => $order['currency'] ?? 'INR',
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'phone' => $user->phone ?? '',
        ]);
    }

    /**
     * Process package sale and update hotel license/validity using HotelService.
     */
    public function storeSale(\App\Http\Requests\StoreDistributorSaleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Security check: ensure hotel belongs to authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::findOrFail($validated['plan_id']);

        // Amount is always computed server-side for gateway sales to prevent tampering
        $computedAmount = round((float) $plan->price * (int) $validated['duration_months'], 2);

        // Online gateway payments must carry a verified Razorpay response
        if (($validated['payment_method'] ?? '') === 'online_gateway') {
            $gateway = PaymentManager::active();

            $payload = $request->only(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature']);

            if (empty($payload['razorpay_order_id']) || empty($payload['razorpay_payment_id'])) {
                return $this->gatewaySaleResponse(
                    $request,
                    false,
                    'Online payment was not completed. No payment reference received from the gateway.'
                );
            }

            if (!$gateway->verifyPayment($payload)) {
                PaymentTransaction::create([
                    'distributor_id' => $user->id,
                    'hotel_admin_id' => $hotel->id,
                    'gateway_code' => $gateway->getCode(),
                    'transaction_id' => (string) $payload['razorpay_payment_id'],
                    'amount' => $computedAmount,
                    'currency' => 'INR',
                    'status' => 'failed',
                    'payload' => $payload,
                    'plan_id' => $plan->id,
                ]);

                return $this->gatewaySaleResponse(
                    $request,
                    false,
                    'Payment verification failed. The transaction was not trusted, license was not activated.'
                );
            }

            // Prevent duplicate processing of the same gateway payment
            $alreadyProcessed = PaymentTransaction::where('transaction_id', $payload['razorpay_payment_id'])
                ->where('status', 'success')
                ->exists();

            if ($alreadyProcessed) {
                return $this->gatewaySaleResponse(
                    $request,
                    false,
                    'This payment was already processed. Duplicate submission blocked.'
                );
            }

            // Use the verified gateway amount (in rupees) instead of client-sent value
            $validated['amount'] = $computedAmount;
            $validated['notes'] = trim(
                ($validated['notes'] ?? '') .
                " | Razorpay Order: {$payload['razorpay_order_id']}, Payment: {$payload['razorpay_payment_id']}"
            );
        }

        $result = $this->hotelService->assignPlanAndRecordSale(
            $hotel,
            $plan,
            $validated['duration_months'],
            $validated,
            $user->id
        );

        // Record successful gateway transaction in the ledger
        if (($validated['payment_method'] ?? '') === 'online_gateway') {
            PaymentTransaction::create([
                'distributor_id' => $user->id,
                'hotel_admin_id' => $hotel->id,
                'gateway_code' => $gateway->getCode(),
                'transaction_id' => (string) $payload['razorpay_payment_id'],
                'amount' => $computedAmount,
                'currency' => 'INR',
                'status' => 'success',
                'payload' => $payload,
                'plan_id' => $plan->id,
                'paid_at' => now(),
                'idempotency_checked' => true,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Package '{$plan->name}' successfully sold to '{$hotel->hotel_name}'.",
                'sale' => $result['sale']->load(['hotel', 'plan']),
                'new_expiry' => $result['new_expiry']->format('Y-m-d'),
            ], 201);
        }

        return redirect()->route('distributor.sales.index')
                         ->with('success', "Package '{$plan->name}' successfully activated for '{$hotel->hotel_name}'! Valid until {$result['new_expiry']->format('M d, Y')}.");
    }

    /**
     * Uniform response helper for gateway payment failures during a sale.
     */
    private function gatewaySaleResponse(Request $request, bool $success, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $success ? 201 : 422);
        }

        return redirect()->route('distributor.sales.create', $request->only('hotel_id'))
                         ->with('error', $message);
    }

    /**
     * List connected TV devices for hotels registered by this distributor.
     */
    public function devices(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $hotelsQuery = $isSuperAdmin ? HotelAdmin::query() : $user->hotels();
        $hotelIds = $hotelsQuery->pluck('id');

        $query = ConnectedDevice::whereIn('hotel_admin_id', $hotelIds)
            ->with(['hotelAdmin.plan', 'hotelAdmin.distributor']);

        if ($request->filled('hotel_id')) {
            $hotelId = (int) $request->input('hotel_id');
            // Strict authorization check: Only allowed if the hotel belongs to this distributor
            if (!$hotelIds->contains($hotelId)) {
                abort(403, 'Unauthorized. You do not have access to TV devices for this hotel.');
            }
            $query->where('hotel_admin_id', $hotelId);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('room_no', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('hotelAdmin', function ($hq) use ($search) {
                      $hq->where('hotel_name', 'like', "%{$search}%")
                        ->orWhere('license_key', 'like', "%{$search}%");
                  });
            });
        }

        $devices = $query->latest()->paginate(20)->withQueryString();
        $hotels = $hotelsQuery->orderBy('hotel_name')->get();
        $selectedHotel = $request->filled('hotel_id') ? $hotels->firstWhere('id', (int) $request->input('hotel_id')) : null;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $devices,
            ]);
        }

        return view('distributor.devices.index', compact('devices', 'hotels', 'selectedHotel'));
    }

    /**
     * Disconnect a device belonging to a hotel onboarded by this distributor.
     */
    public function destroyDevice(int $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $hotelIds = $isSuperAdmin ? HotelAdmin::pluck('id') : $user->hotels()->pluck('id');

        // Strict authorization: Ensure device belongs to a hotel managed by this distributor
        $device = ConnectedDevice::whereIn('hotel_admin_id', $hotelIds)->findOrFail($id);
        $roomNo = $device->room_no;
        $device->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Device for Room {$roomNo} disconnected successfully."
            ]);
        }

        return redirect()->back()->with('success', "Device for Room {$roomNo} disconnected successfully.");
    }
}
