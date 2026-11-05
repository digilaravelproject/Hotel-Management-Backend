<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\User;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelAdminController extends Controller
{
    /**
     * Inject reusable HotelService layer.
     */
    public function __construct(
        protected HotelService $hotelService
    ) {}

    /**
     * Display a listing of the hotel admins.
     */
    public function index()
    {
        $hotels = HotelAdmin::with(['plan', 'distributor', 'connectedDevices'])
            ->orderBy('created_at', 'desc')
            ->get();

        try {
            $distributors = User::role('distributor')->orderBy('name', 'asc')->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->orderBy('name', 'asc')->get();
        }

        return view('super_admin.hotels.index', compact('hotels', 'distributors'));
    }

    /**
     * Show the form for creating a new hotel admin.
     */
    public function create()
    {
        $plans = Plan::with('tax')->where('status', '=', true)->orderBy('price', 'asc')->get();
        try {
            $distributors = User::role('distributor')->where('status', true)->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->where('status', true)->get();
        }
        $themes = \App\Models\TvTemplate::select('theme_id', 'theme_name')->orderBy('theme_id', 'asc')->get()->unique('theme_id')->values();
        $defaultThemeId = (int) app_setting('default_theme_id', 1);

        return view('super_admin.hotels.create', compact('plans', 'distributors', 'themes', 'defaultThemeId'));
    }

    /**
     * Store a newly created hotel admin in storage using FormRequest and Service.
     */
    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelService->createHotel(
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image')
        );

        if ($request->input('payment_action') === 'pay_now' && $hotel->plan_id) {
            return redirect()->route('super-admin.hotels.pay', $hotel->id);
        }

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Vendor registered successfully! (Status: Waiting for Payment)');
    }

    /**
     * Display the specified hotel admin.
     */
    public function show(int $id)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor', 'connectedDevices'])->findOrFail($id);

        return view('super_admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel admin.
     */
    public function edit(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $plans = Plan::with('tax')->where('status', '=', true)->orderBy('price', 'asc')->get();
        try {
            $distributors = User::role('distributor')->where('status', true)->get();
        } catch (\Throwable $e) {
            $distributors = User::whereHas('roles', fn ($q) => $q->where('name', 'distributor'))->where('status', true)->get();
        }
        $themes = \App\Models\TvTemplate::select('theme_id', 'theme_name')->orderBy('theme_id', 'asc')->get()->unique('theme_id')->values();
        $defaultThemeId = (int) app_setting('default_theme_id', 1);

        return view('super_admin.hotels.edit', compact('hotel', 'plans', 'distributors', 'themes', 'defaultThemeId'));
    }

    /**
     * Update the specified hotel admin in storage using FormRequest and Service.
     */
    public function update(UpdateHotelRequest $request, int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);

        $updatedHotel = $this->hotelService->updateHotel(
            $hotel,
            $request->validated(),
            $request->file('hotel_logo'),
            $request->file('hotel_image'),
            $request->file('slider_images', [])
        );

        if ($request->input('payment_action') === 'pay_now' && $updatedHotel->plan_id && $updatedHotel->payment_status !== 'paid') {
            return redirect()->route('super-admin.hotels.pay', $updatedHotel->id);
        }

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel details updated successfully!');
    }

    /**
     * Show Super Admin instant checkout for a hotel subscription.
     */
    public function showPayment(int $id)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor'])->findOrFail($id);

        if (!$hotel->plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign a subscription plan first.');
        }

        $tvCount = max(1, (int) ($hotel->room_count ?: 1));
        $pricing = $hotel->plan->calculatePricing($tvCount);

        return view('super_admin.hotels.pay', compact('hotel', 'tvCount', 'pricing'));
    }

    /**
     * Process Super Admin instant checkout and generate license + invoice.
     */
    public function processPayment(Request $request, int $id, \App\Services\SubscriptionService $subService)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor'])->findOrFail($id);
        $plan = $hotel->plan;

        if (!$plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign a subscription plan first.');
        }

        $tvCount = max(1, (int) ($hotel->room_count ?: 1));
        $pricing = $plan->calculatePricing($tvCount);
        $durationMonths = ($plan->billing_cycle === 'yearly') ? 12 : 1;

        $subService->activate($hotel, $plan, $durationMonths, [
            'tv_count' => $tvCount,
            'amount' => $pricing['total_amount'],
            'payment_method' => $request->input('payment_method', 'super_admin_checkout'),
            'gateway_code' => 'SUPER_ADMIN_PAYMENT',
            'transaction_id' => 'TXN_' . strtoupper(\Illuminate\Support\Str::random(12)),
            'notes' => "Super Admin instant checkout for {$tvCount} TVs ({$plan->name})",
        ]);

        return redirect()->route('super-admin.hotels.show', $hotel->id)
            ->with('success', "Payment settled successfully! TV Pairing License generated and {$tvCount} TV slots authorized.");
    }

    /**
     * Show Super Admin TV Capacity Add-on screen for an active hotel.
     */
    public function showAddon(int $id, \App\Services\SubscriptionService $subService)
    {
        $hotel = HotelAdmin::with(['plan.tax', 'distributor', 'connectedDevices'])->findOrFail($id);

        if (!$hotel->plan) {
            return redirect()->route('super-admin.hotels.edit', $hotel->id)
                ->with('error', 'Please assign an active subscription plan first before adding TV slots.');
        }

        $now = now();
        $hasActivePlan = $hotel->expiry_date && $hotel->expiry_date->gt($now);
        $remainingDays = $hasActivePlan ? max(1, (int) $now->diffInDays($hotel->expiry_date, false)) : 30;

        $defaultAdditionalTvs = 10;
        $pricing = $subService->calculateAddonPricing($hotel, $defaultAdditionalTvs);

        return view('super_admin.hotels.addon', compact('hotel', 'defaultAdditionalTvs', 'pricing', 'remainingDays'));
    }

    /**
     * Process Super Admin TV Add-on and update hotel device quota.
     */
    public function processAddon(Request $request, int $id, \App\Services\SubscriptionService $subService)
    {
        $request->validate([
            'additional_tvs' => 'required|integer|min:1|max:5000',
            'is_complimentary' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $hotel = HotelAdmin::with(['plan.tax', 'distributor'])->findOrFail($id);
        $additionalTvs = (int) $request->input('additional_tvs');
        $isComplimentary = $request->boolean('is_complimentary');

        $result = $subService->addTvAddon($hotel, $additionalTvs, [
            'is_complimentary' => $isComplimentary,
            'payment_method' => $isComplimentary ? 'complimentary_grant' : 'super_admin_manual',
            'notes' => $request->input('notes') ?: ($isComplimentary ? "Super Admin complimentary TV add-on (+{$additionalTvs} TVs)" : "Super Admin TV Add-On (+{$additionalTvs} TVs)"),
        ]);

        return redirect()->route('super-admin.hotels.show', $hotel->id)
            ->with('success', "TV Add-On applied! {$additionalTvs} extra TV slots authorized. Total capacity is now {$result['new_tv_count']} TVs.");
    }

    /**
     * Toggle the status of a hotel admin (active/inactive).
     */
    public function toggleStatus(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $newStatus = $this->hotelService->toggleStatus($hotel);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status updated to ' . ($newStatus ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Update the approval status of a hotel admin.
     */
    public function toggleApproval(Request $request, int $id)
    {
        $request->validate([
            'approval_status' => 'required|in:pending,approved,disapproved'
        ]);

        $hotel = HotelAdmin::findOrFail($id);
        $approvalStatus = $this->hotelService->updateApprovalStatus($hotel, $request->approval_status);

        return response()->json([
            'success' => true,
            'approval_status' => $approvalStatus,
            'message' => 'Approval status updated to ' . ucfirst($approvalStatus)
        ]);
    }

    /**
     * Remove the specified hotel admin from storage.
     */
    public function destroy(int $id)
    {
        $hotel = HotelAdmin::findOrFail($id);
        $this->hotelService->deleteHotel($hotel);

        return redirect()->route('super-admin.hotels.index')
                         ->with('success', 'Hotel Admin deleted successfully!');
    }
}
