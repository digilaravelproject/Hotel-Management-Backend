<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'distributor_wallet_transactions';

    protected $fillable = [
        'distributor_id',
        'distributor_sale_id',
        'type', // 'credit', 'debit'
        'amount',
        'balance_after',
        'reference_type', // 'sale', 'withdrawal', 'withdrawal_refund', 'manual'
        'reference_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function distributorSale()
    {
        return $this->belongsTo(DistributorSale::class, 'distributor_sale_id');
    }

    public function withdrawalRequest()
    {
        return $this->belongsTo(WithdrawalRequest::class, 'reference_id');
    }

    public function wallet()
    {
        return $this->belongsTo(DistributorWallet::class, 'distributor_id', 'distributor_id');
    }

    /**
     * Determine if this transaction is related to a plan renewal.
     */
    public function getIsRenewalAttribute(): bool
    {
        $sale = $this->distributorSale;
        if ($sale) {
            $notes = strtolower($sale->notes ?? '');
            if (str_contains($notes, 'renewal') || str_contains($notes, 'renew')) {
                return true;
            }
        }
        return str_contains(strtolower($this->description ?? ''), 'renewal') || str_contains(strtolower($this->description ?? ''), 'renew');
    }

    /**
     * Resolve associated hotel model.
     */
    public function getHotelAttribute(): ?HotelAdmin
    {
        return $this->distributorSale?->hotel;
    }

    /**
     * Resolve associated plan model.
     */
    public function getPlanAttribute(): ?Plan
    {
        return $this->distributorSale?->plan;
    }
}
