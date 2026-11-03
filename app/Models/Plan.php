<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OttPlatform;
use App\Models\Tax;

class Plan extends Model
{
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'billing_cycle',
        'price_per_tv',
        'tax_id',
        'tax_type',
        'room_count',
        'price',
        'status',
        'description',
        'ott_platforms',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'price_per_tv' => 'decimal:2',
        'room_count' => 'integer',
        'ott_platforms' => 'array',
    ];

    /**
     * Get effective per-TV price with backwards compatibility for legacy price column.
     */
    public function getEffectivePricePerTvAttribute(): float
    {
        if (isset($this->attributes['price_per_tv']) && (float) $this->attributes['price_per_tv'] > 0) {
            return (float) $this->attributes['price_per_tv'];
        }
        return (float) ($this->attributes['price'] ?? 150.00);
    }

    /**
     * Relationship: Tax assigned to this plan
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    /**
     * Calculate dynamic pricing for N number of TVs including tax breakdown
     */
    public function calculatePricing(int $tvCount = 1): array
    {
        $tvCount = max(1, $tvCount);
        $ratePerTv = $this->effective_price_per_tv;
        $subtotal = round($ratePerTv * $tvCount, 2);

        $tax = $this->tax;
        $taxType = $this->tax_type ?? 'exclusive';

        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            return array_merge([
                'tv_count' => $tvCount,
                'price_per_tv' => $ratePerTv,
                'billing_cycle' => $this->billing_cycle ?? 'monthly',
                'tax_id' => $tax->id,
                'tax_name' => $tax->name,
                'tax_rate' => (float) $tax->rate,
                'tax_type' => $taxType,
            ], $taxCalc);
        }

        return [
            'tv_count' => $tvCount,
            'price_per_tv' => $ratePerTv,
            'billing_cycle' => $this->billing_cycle ?? 'monthly',
            'subtotal' => $subtotal,
            'tax_id' => null,
            'tax_name' => 'No Tax',
            'tax_rate' => 0.0,
            'tax_type' => $taxType,
            'tax_amount' => 0.0,
            'cgst_amount' => 0.0,
            'sgst_amount' => 0.0,
            'total_amount' => $subtotal,
        ];
    }

    /**
     * Get the list of available OTT platforms dynamically from database master table.
     *
     * @return array<int, array<string, string>>
     */
    public static function getAvailableOttPlatforms(): array
    {
        try {
            return OttPlatform::query()
                ->where('status', true)
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($platform) {
                    return [
                        'id' => $platform->id,
                        'name' => $platform->name,
                        'package' => $platform->package_name,
                        'icon' => $platform->icon_url,
                    ];
                })
                ->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Get the hotel admins subscribed to this plan.
     */
    public function hotelAdmins()
    {
        return $this->hasMany(HotelAdmin::class, 'plan_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'plan_id');
    }
}
