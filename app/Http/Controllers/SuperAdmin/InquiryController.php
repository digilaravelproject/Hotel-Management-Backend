<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInquiry;

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
     * Remove the specified inquiry from database.
     */
    public function destroy($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->delete();

        return back()->with('success', 'Inquiry deleted successfully.');
    }
}

