<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(protected WalletService $walletService) {}

    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = WithdrawalRequest::with('distributor')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $withdrawals = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $withdrawals]);
        }

        return view('super_admin.withdrawals.index', compact('withdrawals', 'status'));
    }

    public function approve(Request $request, int $id)
    {
        $validated = $request->validate([
            'transaction_number' => 'required|string|max:100',
            'admin_notes' => 'nullable|string',
        ]);

        $withdrawal = WithdrawalRequest::findOrFail($id);

        try {
            $this->walletService->approveWithdrawal(
                $withdrawal,
                $validated['transaction_number'],
                $validated['admin_notes'] ?? null
            );

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Withdrawal approved and funds deducted successfully.']);
            }

            return redirect()->back()->with('success', 'Withdrawal request approved successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $withdrawal = WithdrawalRequest::findOrFail($id);

        try {
            $this->walletService->rejectWithdrawal(
                $withdrawal,
                $validated['admin_notes']
            );

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Withdrawal request rejected and funds returned.']);
            }

            return redirect()->back()->with('success', 'Withdrawal request rejected successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
