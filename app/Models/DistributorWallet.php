<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorWallet extends Model
{
    use HasFactory;

    protected $table = 'distributor_wallets';

    protected $fillable = [
        'distributor_id',
        'balance',
        'locked_balance',
        'total_earned',
        'total_withdrawn',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'locked_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function transactions()
    {
        return $this->hasMany(DistributorWalletTransaction::class, 'distributor_id', 'distributor_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'distributor_id', 'distributor_id');
    }

    /**
     * Net spendable balance (balance minus funds locked in pending withdrawal requests).
     */
    public function getAvailableBalanceAttribute(): float
    {
        return max(0, round((float) $this->balance - (float) ($this->locked_balance ?? 0), 2));
    }

    /**
     * Static helper to find or create wallet for distributor.
     */
    public static function getForDistributor(int $distributorId): self
    {
        return static::firstOrCreate(
            ['distributor_id' => $distributorId],
            [
                'balance' => 0.00,
                'locked_balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]
        );
    }
}

