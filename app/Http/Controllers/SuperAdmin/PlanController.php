<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Tax;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        $plans = Plan::with('tax')->orderBy('billing_cycle', 'asc')->orderBy('price_per_tv', 'asc')->get();
        $taxes = Tax::where('status', true)->orderBy('name')->get();
        return view('super_admin.plans.index', compact('plans', 'taxes'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        $ottPlatforms = Plan::getAvailableOttPlatforms();
        $taxes = Tax::where('status', true)->orderBy('name')->get();
        return view('super_admin.plans.create', compact('ottPlatforms', 'taxes'));
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,yearly',
            'price_per_tv' => 'required|numeric|min:0',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_type' => 'required|in:inclusive,exclusive',
            'room_count' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $pricePerTv = (float) $request->price_per_tv;

        Plan::create([
            'name' => $request->name,
            'billing_cycle' => $request->billing_cycle,
            'price_per_tv' => $pricePerTv,
            'price' => $pricePerTv, // sync base price
            'room_count' => $request->input('room_count', 1),
            'tax_id' => $request->tax_id,
            'tax_type' => $request->tax_type,
            'description' => $request->description,
            'ott_platforms' => $request->input('ott_platforms', []),
            'status' => true,
        ]);

        return redirect()->route('super-admin.plans.index')
                         ->with('success', 'Per-TV Plan created successfully!');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit($id)
    {
        $plan = Plan::with('tax')->findOrFail($id);
        $ottPlatforms = Plan::getAvailableOttPlatforms();
        $taxes = Tax::where('status', true)->orderBy('name')->get();
        return view('super_admin.plans.edit', compact('plan', 'ottPlatforms', 'taxes'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,yearly',
            'price_per_tv' => 'required|numeric|min:0',
            'tax_id' => 'nullable|exists:taxes,id',
            'tax_type' => 'required|in:inclusive,exclusive',
            'room_count' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $pricePerTv = (float) $request->price_per_tv;

        $plan->update([
            'name' => $request->name,
            'billing_cycle' => $request->billing_cycle,
            'price_per_tv' => $pricePerTv,
            'price' => $pricePerTv,
            'room_count' => $request->input('room_count', $plan->room_count ?? 1),
            'tax_id' => $request->tax_id,
            'tax_type' => $request->tax_type,
            'description' => $request->description,
            'ott_platforms' => $request->input('ott_platforms', []),
        ]);

        return redirect()->route('super-admin.plans.index')
                         ->with('success', 'Per-TV Plan updated successfully!');
    }

    /**
     * Live JSON price preview endpoint for any plan given a TV count.
     */
    public function calculatePreview(Request $request, $id)
    {
        $plan = Plan::with('tax')->findOrFail($id);
        $tvCount = max(1, (int) $request->input('tv_count', 1));
        $pricing = $plan->calculatePricing($tvCount);

        return response()->json([
            'success' => true,
            'pricing' => $pricing,
        ]);
    }

    /**
     * Toggle the status of a plan (active/inactive).
     */
    public function toggleStatus($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->status = !$plan->status;
        $plan->save();

        return response()->json([
            'success' => true,
            'status' => $plan->status,
            'message' => 'Plan status updated to ' . ($plan->status ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        
        // Prevent deletion if hotels are subscribed
        if ($plan->hotelAdmins()->count() > 0) {
            return back()->with('error', 'Cannot delete this plan as it is linked to ' . $plan->hotelAdmins()->count() . ' hotel(s).');
        }

        $plan->delete();

        return redirect()->route('super-admin.plans.index')
                         ->with('success', 'Plan deleted successfully!');
    }
}
