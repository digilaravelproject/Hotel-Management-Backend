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
        return view('super_admin.invoices.index', compact('invoices'));
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

