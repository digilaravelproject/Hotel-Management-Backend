<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\DistributorWallet;
use App\Models\WithdrawalRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Distributor digital wallet dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $wallet = DistributorWallet::getForDistributor($user->id);

        $recentTransactions = $wallet->transactions()->latest()->take(10)->get();
        $transactions = $wallet->transactions()->latest()->paginate(15);

        $recentWithdrawals = $wallet->withdrawalRequests()->latest()->take(5)->get();
        $withdrawals = $wallet->withdrawalRequests()->latest()->paginate(15);

        $metrics = [
            'balance' => (float) $wallet->balance,
            'locked_balance' => (float) $wallet->locked_balance,
            'available_balance' => (float) $wallet->available_balance,
            'total_earned' => (float) $wallet->total_earned,
            'total_withdrawn' => (float) $wallet->total_withdrawn,
            'commission_rate' => $user->effective_commission_percentage,
            'is_override' => ($user->commission_percentage !== null),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'wallet' => $wallet,
                'metrics' => $metrics,
                'recent_transactions' => $recentTransactions,
                'transactions' => $transactions,
                'recent_withdrawals' => $recentWithdrawals,
                'withdrawal_requests' => $withdrawals,
            ]);
        }

        return view('distributor.wallet.index', compact(
            'wallet',
            'metrics',
            'recentTransactions',
            'transactions',
            'recentWithdrawals',
            'withdrawals'
        ));
    }

    /**
     * Paginated transaction ledger history.
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();
        $wallet = DistributorWallet::getForDistributor($user->id);
        $query = $wallet->transactions()->latest();

        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $transactions,
            ]);
        }

        return view('distributor.wallet.transactions', compact('transactions'));
    }

    /**
     * Paginated withdrawal requests history.
     */
    public function withdrawals(Request $request)
    {
        $user = auth()->user();
        $wallet = DistributorWallet::getForDistributor($user->id);
        $query = $wallet->withdrawalRequests()->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $withdrawals,
            ]);
        }

        return view('distributor.wallet.withdrawals', compact('withdrawals'));
    }

    /**
     * Submit a new withdrawal request.
     */
    public function requestWithdrawal(Request $request)
    {
        $user = auth()->user();
        $wallet = DistributorWallet::getForDistributor($user->id);

        // Resolve account details string from either direct input or discrete form fields
        $accountDetails = $request->input('account_details');
        if (empty($accountDetails)) {
            $method = $request->input('payment_method', 'bank_transfer');
            if ($method === 'upi' && $request->filled('upi_id')) {
                $accountDetails = "UPI ID: " . $request->input('upi_id');
            } elseif ($request->filled('account_number')) {
                $accountDetails = "Bank: " . ($request->input('bank_name') ?: 'Bank') . 
                                  " | A/C: " . $request->input('account_number') . 
                                  " | Name: " . ($request->input('account_holder_name') ?: $user->name) . 
                                  " | IFSC: " . $request->input('ifsc_code');
            } elseif ($request->filled('upi_id')) {
                $accountDetails = "UPI ID: " . $request->input('upi_id');
            }
        }

        // Merge resolved account_details into request for validation
        if ($accountDetails) {
            $request->merge(['account_details' => $accountDetails]);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:50',
            'account_details' => 'required|string|max:500',
        ], [
            'amount.required' => 'Please enter a valid withdrawal amount.',
            'amount.min' => 'Withdrawal amount must be at least ₹1.',
            'account_details.required' => 'Please provide complete bank account details or UPI ID.',
        ]);

        $amount = (float) $validated['amount'];
        $available = $wallet->available_balance;

        if ($amount > $available) {
            $errorMsg = "Insufficient available balance. You have ₹" . number_format($available, 2) . " available.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors(['amount' => $errorMsg])->withInput();
        }

        try {
            $withdrawal = $this->walletService->requestWithdrawal(
                $user->id,
                $amount,
                $validated['payment_method'],
                $validated['account_details']
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Withdrawal request submitted successfully.',
                    'withdrawal' => $withdrawal,
                ], 201);
            }

            return redirect()->route('distributor.wallet.index')->with('success', 'Withdrawal request submitted successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
            }

            return redirect()->back()->withErrors(['amount' => $e->getMessage()])->withInput();
        }
    }
}
