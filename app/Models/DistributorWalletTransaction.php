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

    public function wallet()
    {
        return $this->belongsTo(DistributorWallet::class, 'distributor_id', 'distributor_id');
    }
}
