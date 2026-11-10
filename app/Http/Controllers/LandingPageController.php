<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class LandingPageController extends Controller
{
    /**
     * Display the official Pax TV Launcher website.
     */
    public function web()
    {
        return view('web.home');
    }

    /**
     * Display the landing page with active plans.
     */
    public function index()
    {
        $plans = Plan::where('status', true)->where('is_hidden', false)->orderBy('room_count', 'asc')->get();
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

        // Suggest the smallest visible plan that fits the room requirement
        $suggestedPlan = Plan::where('status', true)
            ->where('is_hidden', false)
            ->where('room_count', '>=', $rooms)
            ->orderBy('room_count', 'asc')
            ->first();

        // Fallback to the largest visible plan if the rooms exceed all existing limits
        if (!$suggestedPlan) {
            $suggestedPlan = Plan::where('status', true)
                ->where('is_hidden', false)
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
     * Handle Contact Us / Demo Request form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'hotel_name' => 'nullable|string|max:150',
            'property' => 'nullable|string|max:150',
            'rooms' => 'nullable|integer|min:1',
            'subject' => 'nullable|string|max:200',
            'message' => 'nullable|string|max:3000',
        ]);

        $hotelName = $validated['hotel_name'] ?? ($validated['property'] ?? 'Not specified');
        $subject = $validated['subject'] ?? 'Pax TV Launcher Demo Request';

        \Illuminate\Support\Facades\Log::info('Public Website / Demo Form Submission:', array_merge($validated, [
            'hotel_name' => $hotelName,
            'subject' => $subject,
        ]));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your request has been received. Our team will contact you shortly.',
            ]);
        }

        return back()->with('success', 'Thank you! Your request has been received. Our team will get back to you within 24 hours.');
    }
}
