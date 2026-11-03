<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'hotel_admin_id',
        'distributor_id',
        'plan_id',
        'plan_name',
        'billing_cycle',
        'tv_count',
        'price_per_tv',
        'subtotal',
        'tax_id',
        'tax_name',
        'tax_rate',
        'tax_type',
        'tax_amount',
        'cgst_amount',
        'sgst_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'paid_at',
        'period_start',
        'period_end',
        'notes',
    ];

    protected $casts = [
        'price_per_tv' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
    ];

    /**
     * Auto-generate standard unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Y') . '-';
        $latest = static::where('invoice_number', 'LIKE', $prefix . '%')->latest('id')->first();
        if ($latest) {
            $numberPart = (int) substr($latest->invoice_number, strlen($prefix));
            $nextNumber = str_pad($numberPart + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        return $prefix . $nextNumber;
    }

    public function hotel()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}

