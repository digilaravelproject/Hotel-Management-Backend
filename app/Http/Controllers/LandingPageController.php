<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\ContactInquiry;
use App\Models\MailSetting;
use App\Mail\DemoRequestThankYouMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
     * Display the dedicated Thank You page after form submission.
     */
    public function thankYou()
    {
        return view('web.thankyou');
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
        // 0. Honeypot Anti-Spam Check: If a bot fills hidden honeypot fields, drop silently
        $honeypot = $request->input('website_url') 
                 ?? $request->input('form_fields.website_url') 
                 ?? $request->input('website_hp');

        if (!empty($honeypot)) {
            Log::info('Spam bot blocked via honeypot:', [
                'ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 150),
            ]);

            // Fake success response so bot believes submission succeeded without polluting DB or triggering emails
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you! Your request has been received.',
                    'redirect_url' => route('thank-you'),
                ]);
            }

            return redirect()->route('thank-you')->with('success', 'Thank you! Your demo request has been received.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
        ]);

        // 1. Cloudflare Turnstile Verification (if enabled in Super Admin settings)
        $turnstile = app(\App\Services\TurnstileService::class);
        if ($turnstile->isEnabled()) {
            $token = $request->input('cf-turnstile-response');
            if (!$turnstile->verify($token, $request->ip())) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Security bot verification failed. Please refresh and try again.',
                    ], 422);
                }

                return redirect()->back()->withInput()->withErrors([
                    'cf-turnstile-response' => 'Security bot verification failed. Please try again.',
                ]);
            }
        }

        // 2. Save directly to database
        $inquiry = ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        Log::info('Public Website Lead Saved:', ['id' => $inquiry->id, 'email' => $inquiry->email]);

        // 2. Send Thank You Email to the user (non-blocking in case mail server is offline)
        try {
            if (class_exists(MailSetting::class)) {
                MailSetting::getSettings()->applyConfig();
            }

            Mail::to($inquiry->email)->send(new DemoRequestThankYouMail($inquiry));
            Log::info("Thank-you email successfully dispatched to {$inquiry->email} for inquiry #{$inquiry->id}");
        } catch (\Throwable $e) {
            Log::warning("Could not dispatch thank-you email to {$inquiry->email}: " . $e->getMessage());
        }

        // 3. Return response with redirect to thank-you page
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your request has been received.',
                'redirect_url' => route('thank-you'),
                'inquiry_id' => $inquiry->id,
            ]);
        }

        return redirect()->route('thank-you')->with('success', 'Thank you! Your demo request has been received.');
    }
}
