<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfQuarter = $now->copy()->firstOfQuarter();
        $endOfQuarter = $now->copy()->lastOfQuarter();

        // 1. Month Invoices Count (Total invoices created this month)
        $monthInvoicesCount = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 2. This Month Revenue (Paid invoices total this month)
        $monthRevenue = (float) Invoice::where('payment_status', 'paid')
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                  ->orWhere(function ($sq) use ($startOfMonth, $endOfMonth) {
                      $sq->whereNull('paid_at')
                         ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                  });
            })
            ->sum('total_amount');

        // 3. This Quarter Revenue (Paid invoices total this quarter)
        $quarterRevenue = (float) Invoice::where('payment_status', 'paid')
            ->where(function ($q) use ($startOfQuarter, $endOfQuarter) {
                $q->whereBetween('paid_at', [$startOfQuarter, $endOfQuarter])
                  ->orWhere(function ($sq) use ($startOfQuarter, $endOfQuarter) {
                      $sq->whereNull('paid_at')
                         ->whereBetween('created_at', [$startOfQuarter, $endOfQuarter]);
                  });
            })
            ->sum('total_amount');

        // 4. Overall Lifetime Revenue (All-time paid revenue)
        $overallRevenue = (float) Invoice::where('payment_status', 'paid')
            ->sum('total_amount');

        // 5. Total Tax Collected (CGST + SGST on paid invoices)
        $totalTaxCollected = (float) Invoice::where('payment_status', 'paid')
            ->sum('tax_amount');

        // 6. Pending / Unpaid Invoices (Pending count and dues)
        $pendingInvoicesCount = Invoice::where('payment_status', '!=', 'paid')->count();
        $pendingInvoicesAmount = (float) Invoice::where('payment_status', '!=', 'paid')->sum('total_amount');

        $metrics = [
            'month_invoices_count' => $monthInvoicesCount,
            'month_revenue' => $monthRevenue,
            'quarter_revenue' => $quarterRevenue,
            'overall_revenue' => $overallRevenue,
            'total_tax_collected' => $totalTaxCollected,
            'pending_count' => $pendingInvoicesCount,
            'pending_amount' => $pendingInvoicesAmount,
            'quarter_name' => 'Q' . $now->quarter . ' ' . $now->year,
            'month_name' => $now->format('M Y'),
        ];

        $query = Invoice::with(['hotel', 'distributor', 'plan', 'tax'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('hotel', fn($hq) => $hq->where('hotel_name', 'LIKE', "%{$search}%"));
            });
        }

        $invoices = $query->paginate(15);
        return view('super_admin.invoices.index', compact('invoices', 'metrics'));
    }

    /**
     * Display the specified invoice detail / print view.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['hotel', 'distributor', 'plan', 'tax'])->findOrFail($id);
        return view('super_admin.invoices.show', compact('invoice'));
    }

    /**
     * Mark an invoice as paid.
     */
    public function markPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->input('payment_method', 'manual_admin'),
        ]);

        return back()->with('success', "Invoice #{$invoice->invoice_number} marked as paid successfully.");
    }
}

