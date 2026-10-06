<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorSale extends Model
{
    use HasFactory;

    protected $table = 'distributor_sales';

    protected $fillable = [
        'distributor_id',
        'hotel_id',
        'plan_id',
        'amount',
        'payment_status',
        'payment_method',
        'license_key_issued',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function hotel()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
