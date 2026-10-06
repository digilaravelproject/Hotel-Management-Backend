<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DistributorController extends Controller
{
    /**
     * Inject reusable service layer.
     */
    public function __construct(
        protected \App\Services\DashboardService $dashboardService
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
     * Register a new hotel under this distributor.
     */
    public function storeHotel(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:hotel_admins,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'hotel_location' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'room_count' => 'required|integer|min:1|max:1000',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        // Generate unique license key
        do {
            $licenseKey = 'DIST-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (HotelAdmin::where('license_key', $licenseKey)->exists());

        $plan = !empty($validated['plan_id']) ? Plan::find($validated['plan_id']) : null;
        $now = now();
        $expiry = $plan ? $now->copy()->addDays(30) : null;

        $hotel = HotelAdmin::create([
            'distributor_id' => $user->id,
            'hotel_name' => $validated['hotel_name'],
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'hotel_location' => $validated['hotel_location'],
            'city' => $validated['city'] ?? $validated['hotel_location'],
            'room_count' => $plan ? max($validated['room_count'], $plan->room_count) : $validated['room_count'],
            'plan_id' => $plan ? $plan->id : null,
            'license_key' => $licenseKey,
            'approval_status' => 'approved',
            'status' => true,
            'payment_status' => $plan ? 'paid' : 'pending',
            'purchase_date' => $plan ? $now : null,
            'expiry_date' => $expiry,
        ]);

        // If a plan was selected during onboarding, record it in sales ledger
        if ($plan) {
            DistributorSale::create([
                'distributor_id' => $user->id,
                'hotel_id' => $hotel->id,
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'payment_status' => 'completed',
                'payment_method' => 'distributor_direct',
                'license_key_issued' => $licenseKey,
                'notes' => "Initial onboarding package: {$plan->name}",
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Hotel '{$hotel->hotel_name}' onboarded successfully.",
                'data' => $hotel->load('plan'),
            ], 201);
        }

        return redirect()->route('distributor.hotels.index')
                         ->with('success', "Hotel '{$hotel->hotel_name}' registered successfully! License Key: {$licenseKey}");
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
     * Process package sale and update hotel license/validity.
     */
    public function storeSale(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotel_admins,id',
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        // Security check: ensure hotel belongs to authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::findOrFail($validated['plan_id']);

        $durationDays = $validated['duration_months'] * 30;
        $now = now();

        // Calculate new expiry: if existing expiry is in the future, extend it; else start from now
        $baseDate = ($hotel->expiry_date && $hotel->expiry_date > $now) ? $hotel->expiry_date : $now;
        $newExpiry = $baseDate->copy()->addDays($durationDays);

        // Update hotel plan details
        $hotel->plan_id = $plan->id;
        $hotel->payment_status = 'paid';
        $hotel->purchase_date = $now;
        $hotel->expiry_date = $newExpiry;
        if ($plan->room_count > $hotel->room_count) {
            $hotel->room_count = $plan->room_count;
        }
        $hotel->save();

        // Record sale in ledger
        $sale = DistributorSale::create([
            'distributor_id' => $user->id,
            'hotel_id' => $hotel->id,
            'plan_id' => $plan->id,
            'amount' => $validated['amount'],
            'payment_status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'license_key_issued' => $hotel->license_key,
            'notes' => $validated['notes'] ?? "Package sale: {$plan->name} ({$validated['duration_months']} months)",
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Package '{$plan->name}' successfully sold to '{$hotel->hotel_name}'.",
                'sale' => $sale->load(['hotel', 'plan']),
                'new_expiry' => $newExpiry->format('Y-m-d'),
            ], 201);
        }

        return redirect()->route('distributor.sales.index')
                         ->with('success', "Package '{$plan->name}' successfully activated for '{$hotel->hotel_name}'! Valid until {$newExpiry->format('M d, Y')}.");
    }
}
