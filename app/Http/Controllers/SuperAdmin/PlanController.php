<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        $plans = Plan::orderBy('room_count', 'asc')->get();
        return view('super_admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        $ottPlatforms = Plan::getAvailableOttPlatforms();
        return view('super_admin.plans.create', compact('ottPlatforms'));
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'room_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        Plan::create([
            'name' => $request->name,
            'room_count' => $request->room_count,
            'price' => $request->price,
            'description' => $request->description,
            'ott_platforms' => $request->input('ott_platforms', []),
            'status' => true,
        ]);

        return redirect()->route('super-admin.plans.index')
                         ->with('success', 'Plan created successfully!');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $ottPlatforms = Plan::getAvailableOttPlatforms();
        return view('super_admin.plans.edit', compact('plan', 'ottPlatforms'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'room_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'ott_platforms' => 'nullable|array',
            'ott_platforms.*' => 'string',
        ]);

        $plan->update([
            'name' => $request->name,
            'room_count' => $request->room_count,
            'price' => $request->price,
            'description' => $request->description,
            'ott_platforms' => $request->input('ott_platforms', []),
        ]);

        return redirect()->route('super-admin.plans.index')
                         ->with('success', 'Plan updated successfully!');
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
