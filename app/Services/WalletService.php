<?php

namespace App\Services;

use App\Models\DistributorWallet;
use App\Models\DistributorWalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    /**
     * Get or initialize a distributor wallet.
     */
    public function getOrCreateWallet(int $distributorId): DistributorWallet
    {
        return DistributorWallet::firstOrCreate(
            ['distributor_id' => $distributorId],
            [
                'balance' => 0.00,
                'locked_balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]
        );
    }

    /**
     * Atomically credit funds to distributor wallet and write transaction record.
     */
    public function credit(
        int $distributorId,
        float $amount,
        string $referenceType,
        ?int $referenceId,
        string $description,
        ?int $saleId = null
    ): DistributorWalletTransaction {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Credit amount must be greater than zero.");
        }

        return DB::transaction(function () use ($distributorId, $amount, $referenceType, $referenceId, $description, $saleId) {
            // Lock wallet row for update
            $wallet = DistributorWallet::where('distributor_id', $distributorId)->lockForUpdate()->first();

            if (!$wallet) {
                $wallet = DistributorWallet::create([
                    'distributor_id' => $distributorId,
                    'balance' => 0.00,
                    'locked_balance' => 0.00,
                    'total_earned' => 0.00,
                    'total_withdrawn' => 0.00,
                ]);
            }

            $newBalance = round((float) $wallet->balance + $amount, 2);
            $newTotalEarned = round((float) $wallet->total_earned + $amount, 2);

            $wallet->update([
                'balance' => $newBalance,
                'total_earned' => $newTotalEarned,
            ]);

            return DistributorWalletTransaction::create([
                'distributor_id' => $distributorId,
                'distributor_sale_id' => $saleId,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
            ]);
        });
    }

    /**
     * Submit a withdrawal request with pessimistic locking to prevent race condition / double-spending.
     */
    public function requestWithdrawal(
        int $distributorId,
        float $amount,
        string $paymentMethod,
        string $accountDetails
    ): WithdrawalRequest {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Withdrawal amount must be greater than zero.");
        }

        return DB::transaction(function () use ($distributorId, $amount, $paymentMethod, $accountDetails) {
            $wallet = DistributorWallet::where('distributor_id', $distributorId)->lockForUpdate()->first();

            if (!$wallet) {
                throw new InvalidArgumentException("Wallet not found for distributor.");
            }

            $availableBalance = max(0, round((float) $wallet->balance - (float) ($wallet->locked_balance ?? 0), 2));

            if ($amount > $availableBalance) {
                throw new InvalidArgumentException("Insufficient available balance. Available: ₹" . number_format($availableBalance, 2) . ", Requested: ₹" . number_format($amount, 2));
            }

            // Lock requested funds so distributor cannot request again on same balance
            $wallet->update([
                'locked_balance' => round((float) $wallet->locked_balance + $amount, 2),
            ]);

            return WithdrawalRequest::create([
                'distributor_id' => $distributorId,
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'account_details' => $accountDetails,
            ]);
        });
    }

    /**
     * Automatically calculate and credit commission for a distributor sale.
     */
    public function creditCommission(\App\Models\DistributorSale $sale): ?DistributorWalletTransaction
    {
        if (!$sale->distributor_id) {
            return null;
        }

        $distributor = \App\Models\User::find($sale->distributor_id);
        if (!$distributor) {
            return null;
        }

        $commissionService = app(CommissionService::class);
        $result = $commissionService->calculateCommission($distributor, (float) $sale->amount);

        return $this->credit(
            $distributor->id,
            $result['commission_amount'],
            'sale',
            $sale->id,
            "Commission earned from sale #{$sale->id} (Rate: {$result['rate']}%)",
            $sale->id
        );
    }

    /**
     * Admin approves a withdrawal request after manual payment.
     * Deducts balance and locked_balance, marks request as approved, logs debit transaction.
     */
    public function approveWithdrawal(
        WithdrawalRequest $request,
        string $transactionNumber,
        ?string $adminNotes = null
    ): WithdrawalRequest {
        if ($request->status !== 'pending') {
            throw new InvalidArgumentException("Only pending withdrawal requests can be approved.");
        }

        return DB::transaction(function () use ($request, $transactionNumber, $adminNotes) {
            $lockedRequest = WithdrawalRequest::where('id', $request->id)->lockForUpdate()->firstOrFail();
            if ($lockedRequest->status !== 'pending') {
                throw new InvalidArgumentException("Withdrawal request has already been processed.");
            }

            $wallet = DistributorWallet::where('distributor_id', $lockedRequest->distributor_id)->lockForUpdate()->firstOrFail();

            $amount = (float) $lockedRequest->amount;
            $newBalance = max(0, round((float) $wallet->balance - $amount, 2));
            $newLocked = max(0, round((float) $wallet->locked_balance - $amount, 2));
            $newWithdrawn = round((float) $wallet->total_withdrawn + $amount, 2);

            $wallet->update([
                'balance' => $newBalance,
                'locked_balance' => $newLocked,
                'total_withdrawn' => $newWithdrawn,
            ]);

            // Create ledger debit entry
            DistributorWalletTransaction::create([
                'distributor_id' => $lockedRequest->distributor_id,
                'distributor_sale_id' => null,
                'type' => 'debit',
                'amount' => $amount,
                'balance_after' => $newBalance,
                'reference_type' => 'withdrawal',
                'reference_id' => $lockedRequest->id,
                'description' => "Withdrawal payout processed (Tx: {$transactionNumber})",
            ]);

            $lockedRequest->update([
                'status' => 'approved',
                'transaction_number' => $transactionNumber,
                'admin_notes' => $adminNotes,
                'processed_at' => now(),
            ]);

            return $lockedRequest;
        });
    }

    /**
     * Admin rejects a withdrawal request.
     * Releases locked balance back to spendable wallet balance.
     */
    public function rejectWithdrawal(
        WithdrawalRequest $request,
        ?string $adminNotes = null
    ): WithdrawalRequest {
        if ($request->status !== 'pending') {
            throw new InvalidArgumentException("Only pending withdrawal requests can be rejected.");
        }

        return DB::transaction(function () use ($request, $adminNotes) {
            $lockedRequest = WithdrawalRequest::where('id', $request->id)->lockForUpdate()->firstOrFail();
            if ($lockedRequest->status !== 'pending') {
                throw new InvalidArgumentException("Withdrawal request has already been processed.");
            }

            $wallet = DistributorWallet::where('distributor_id', $lockedRequest->distributor_id)->lockForUpdate()->firstOrFail();

            $amount = (float) $lockedRequest->amount;
            $newLocked = max(0, round((float) $wallet->locked_balance - $amount, 2));

            $wallet->update([
                'locked_balance' => $newLocked,
            ]);

            $lockedRequest->update([
                'status' => 'rejected',
                'admin_notes' => $adminNotes,
                'processed_at' => now(),
            ]);

            return $lockedRequest;
        });
    }

    /**
     * Convenience wrapper accepting either ID or model for approving withdrawal.
     */
    public function processWithdrawalApproval(int|WithdrawalRequest $request, string $transactionNumber, ?string $adminNotes = null): WithdrawalRequest
    {
        if (is_numeric($request)) {
            $request = WithdrawalRequest::findOrFail($request);
        }
        return $this->approveWithdrawal($request, $transactionNumber, $adminNotes);
    }

    /**
     * Convenience wrapper accepting either ID or model for rejecting withdrawal.
     */
    public function processWithdrawalRejection(int|WithdrawalRequest $request, ?string $adminNotes = null): WithdrawalRequest
    {
        if (is_numeric($request)) {
            $request = WithdrawalRequest::findOrFail($request);
        }
        return $this->rejectWithdrawal($request, $adminNotes);
    }
}

