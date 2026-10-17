<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_admin_id',
        'distributor_id',
        'gateway_code',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payload',
        'plan_id',
        'paid_at',
        'is_refunded',
        'idempotency_checked',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_refunded' => 'boolean',
        'idempotency_checked' => 'boolean',
        'paid_at' => 'datetime',
    ];
}
