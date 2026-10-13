<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';

    protected $fillable = [
        'distributor_id',
        'amount',
        'status', // 'pending', 'approved', 'rejected'
        'payment_method',
        'account_details',
        'transaction_number',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function wallet()
    {
        return $this->belongsTo(DistributorWallet::class, 'distributor_id', 'distributor_id');
    }
}
