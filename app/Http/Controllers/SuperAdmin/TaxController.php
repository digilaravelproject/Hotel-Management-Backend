<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;

class TaxController extends Controller
{
    /**
     * Display a listing of taxes.
     */
    public function index()
    {
        $taxes = Tax::withCount('plans')->latest()->get();
        return view('super_admin.taxes.index', compact('taxes'));
    }

    /**
     * Store a newly created tax in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'is_split' => 'nullable|boolean',
            'split_name_1' => 'nullable|string|max:50',
            'split_rate_1' => 'nullable|numeric|min:0|max:100',
            'split_name_2' => 'nullable|string|max:50',
            'split_rate_2' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        $isSplit = $request->boolean('is_split');
        $rate = (float) $request->rate;
        $split1 = $isSplit ? (float) ($request->split_rate_1 ?? ($rate / 2)) : null;
        $split2 = $isSplit ? (float) ($request->split_rate_2 ?? ($rate - $split1)) : null;

        if ($isSplit && abs(($split1 + $split2) - $rate) > 0.01) {
            return back()->withInput()->withErrors([
                'split_rate_1' => 'Split tax component rates must sum to the total tax rate (' . $rate . '%).',
            ]);
        }

        if ($request->boolean('is_default')) {
            Tax::where('is_default', true)->update(['is_default' => false]);
        }

        Tax::create([
            'name' => $request->name,
            'rate' => $rate,
            'is_split' => $isSplit,
            'split_name_1' => $isSplit ? ($request->split_name_1 ?? 'CGST') : null,
            'split_rate_1' => $split1,
            'split_name_2' => $isSplit ? ($request->split_name_2 ?? 'SGST') : null,
            'split_rate_2' => $split2,
            'description' => $request->description,
            'is_default' => $request->boolean('is_default'),
            'status' => true,
        ]);

        return redirect()->route('super-admin.taxes.index')
                         ->with('success', 'Tax created successfully!');
    }

    /**
     * Update the specified tax.
     */
    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'is_split' => 'nullable|boolean',
            'split_name_1' => 'nullable|string|max:50',
            'split_rate_1' => 'nullable|numeric|min:0|max:100',
            'split_name_2' => 'nullable|string|max:50',
            'split_rate_2' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        $isSplit = $request->boolean('is_split');
        $rate = (float) $request->rate;
        $split1 = $isSplit ? (float) ($request->split_rate_1 ?? ($rate / 2)) : null;
        $split2 = $isSplit ? (float) ($request->split_rate_2 ?? ($rate - $split1)) : null;

        if ($isSplit && abs(($split1 + $split2) - $rate) > 0.01) {
            return back()->withInput()->withErrors([
                'split_rate_1' => 'Split tax component rates must sum to the total tax rate (' . $rate . '%).',
            ]);
        }

        if ($request->boolean('is_default')) {
            Tax::where('id', '!=', $tax->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $tax->update([
            'name' => $request->name,
            'rate' => $rate,
            'is_split' => $isSplit,
            'split_name_1' => $isSplit ? ($request->split_name_1 ?? 'CGST') : null,
            'split_rate_1' => $split1,
            'split_name_2' => $isSplit ? ($request->split_name_2 ?? 'SGST') : null,
            'split_rate_2' => $split2,
            'description' => $request->description,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('super-admin.taxes.index')
                         ->with('success', 'Tax updated successfully!');
    }

    /**
     * Toggle the status of a tax (active/inactive).
     */
    public function toggleStatus($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->status = !$tax->status;
        $tax->save();

        return response()->json([
            'success' => true,
            'status' => $tax->status,
            'message' => 'Tax status updated to ' . ($tax->status ? 'Active' : 'Inactive')
        ]);
    }

    /**
     * Remove the specified tax.
     */
    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);

        if ($tax->plans()->count() > 0) {
            return back()->with('error', 'Cannot delete this tax because it is assigned to ' . $tax->plans()->count() . ' plan(s).');
        }

        $tax->delete();

        return redirect()->route('super-admin.taxes.index')
                         ->with('success', 'Tax deleted successfully!');
    }
}

