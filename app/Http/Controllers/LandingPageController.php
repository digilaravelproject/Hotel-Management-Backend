<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class LandingPageController extends Controller
{
    /**
     * Display the landing page with active plans.
     */
    public function index()
    {
        $plans = Plan::where('status', true)->orderBy('room_count', 'asc')->get();
        return view('landing.index', compact('plans'));
    }

    /**
     * AJAX endpoint to auto-suggest a plan based on the room count input.
     */
    public function suggestPlan(Request $request)
    {
        $request->validate([
            'room_count' => 'required|integer|min:1',
        ]);

        $rooms = $request->room_count;

        // Suggest the smallest plan that fits the room requirement
        $suggestedPlan = Plan::where('status', true)
            ->where('room_count', '>=', $rooms)
            ->orderBy('room_count', 'asc')
            ->first();

        // Fallback to the largest plan if the rooms exceed all existing limits
        if (!$suggestedPlan) {
            $suggestedPlan = Plan::where('status', true)
                ->orderBy('room_count', 'desc')
                ->first();
        }

        return response()->json([
            'success' => true,
            'plan' => $suggestedPlan
        ]);
    }
}
