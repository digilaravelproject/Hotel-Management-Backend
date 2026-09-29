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

    /**
     * Display the Privacy Policy page.
     */
    public function privacy()
    {
        return view('landing.privacy');
    }

    /**
     * Display the Contact Us page.
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Handle Contact Us form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'hotel_name' => 'nullable|string|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        \Illuminate\Support\Facades\Log::info('Public Contact Us Submission:', $validated);

        return back()->with('success', 'Thank you for reaching out! Your message has been received and our team will get back to you within 24 hours.');
    }
}
