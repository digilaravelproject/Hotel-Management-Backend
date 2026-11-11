<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInquiry;
use App\Mail\DemoRequestThankYouMail;
use App\Mail\MailSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * Display a paginated listing of website demo inquiries / leads.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = ContactInquiry::query();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('hotel_name', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->latest()->paginate(15)->withQueryString();

        // Metrics for summary cards and badges
        $totalCount     = ContactInquiry::count();
        $newCount       = ContactInquiry::where('status', 'new')->count();
        $contactedCount = ContactInquiry::whereIn('status', ['contacted', 'in_progress'])->count();
        $convertedCount = ContactInquiry::where('status', 'converted')->count();

        return view('super_admin.inquiries.index', compact(
            'inquiries',
            'status',
            'search',
            'totalCount',
            'newCount',
            'contactedCount',
            'convertedCount'
        ));
    }

    /**
     * Update the status and admin notes for a specific inquiry.
     */
    public function updateStatus(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);

        $validated = $request->validate([
            'status'      => 'required|in:new,in_progress,contacted,converted,closed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $inquiry->status = $validated['status'];
        if ($request->has('admin_notes')) {
            $inquiry->admin_notes = $validated['admin_notes'];
        }

        if (in_array($validated['status'], ['contacted', 'converted']) && !$inquiry->contacted_at) {
            $inquiry->contacted_at = now();
        }

        $inquiry->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inquiry status updated successfully.',
            ]);
        }

        return back()->with('success', "Inquiry #{$inquiry->id} updated successfully.");
    }

    /**
     * Resend demo thank-you email with fresh signed 7-day download link.
     */
    public function resendEmail($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);

        try {
            if (class_exists(MailSetting::class)) {
                MailSetting::getSettings()->applyConfig();
            }

            Mail::to($inquiry->email)->send(new DemoRequestThankYouMail($inquiry));
            Log::info("Demo email resent to {$inquiry->email} for inquiry #{$inquiry->id} by super admin.");

            return back()->with('success', "Fresh demo email with 7-day download link sent to {$inquiry->email}!");
        } catch (\Throwable $e) {
            Log::error("Failed resending demo email to {$inquiry->email}: " . $e->getMessage());
            return back()->with('error', "Could not send email: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified inquiry from database.
     */
    public function destroy($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->delete();

        return back()->with('success', 'Inquiry deleted successfully.');
    }
}

