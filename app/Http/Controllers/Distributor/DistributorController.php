<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Models\ConnectedDevice;
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
     * Process package sale and update hotel license/validity using HotelService.
     */
    public function storeSale(\App\Http\Requests\StoreDistributorSaleRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Security check: ensure hotel belongs to authenticated distributor
        $hotel = $user->hotels()->findOrFail($validated['hotel_id']);
        $plan = Plan::findOrFail($validated['plan_id']);

        $result = $this->hotelService->assignPlanAndRecordSale(
            $hotel,
            $plan,
            $validated['duration_months'],
            $validated,
            $user->id
        );

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
     * List connected TV devices for hotels registered by this distributor (View Only).
     */
    public function devices(Request $request)
    {
        $user = auth()->user();
        $hotelIds = $user->hotels()->pluck('id');

        $query = ConnectedDevice::whereIn('hotel_admin_id', $hotelIds)
            ->with(['hotelAdmin.plan']);

        if ($request->filled('hotel_id')) {
            $query->where('hotel_admin_id', $request->input('hotel_id'));
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

        $devices = $query->latest()->paginate(15)->withQueryString();
        $hotels = $user->hotels()->orderBy('hotel_name')->get();
        $selectedHotel = $request->filled('hotel_id') ? $hotels->firstWhere('id', $request->input('hotel_id')) : null;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $devices,
            ]);
        }

        return view('distributor.devices.index', compact('devices', 'hotels', 'selectedHotel'));
    }
}
