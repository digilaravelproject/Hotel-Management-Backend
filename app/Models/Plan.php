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
        'is_hidden',
        'description',
        'ott_platforms',
        'is_ott_enabled',
        'allowed_menus',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'is_hidden' => 'boolean',
        'price' => 'decimal:2',
        'price_per_tv' => 'decimal:2',
        'room_count' => 'integer',
        'ott_platforms' => 'array',
        'is_ott_enabled' => 'boolean',
        'allowed_menus' => 'array',
    ];

    /**
     * Scope a query to only include plans visible to hotels, distributors, and public.
     * Hidden plans are only accessible and assignable by Super Admin (e.g. Demo hotels).
     */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

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
     * Headline display rate: Always monthly per TV (₹ / TV / month)
     */
    public function getMonthlyRatePerTvAttribute(): float
    {
        return round($this->effective_price_per_tv, 2);
    }

    /**
     * Annual total calculation for 1 TV (Monthly rate × 12)
     */
    public function getAnnualTotalPerTvAttribute(): float
    {
        return round($this->effective_price_per_tv * 12, 2);
    }

    /**
     * Billed amount per TV for the full duration of this plan's billing cycle.
     * If monthly: monthly rate. If yearly: monthly rate × 12.
     */
    public function getBilledAmountPerTvAttribute(): float
    {
        return ($this->billing_cycle === 'yearly') 
            ? $this->annual_total_per_tv 
            : $this->monthly_rate_per_tv;
    }

    /**
     * Check if a specific menu ID is permitted by this plan.
     * Backwards-compatible: If allowed_menus is null, all menus are granted.
     */
    public function hasMenu(string $menuId): bool
    {
        if ($this->allowed_menus === null) {
            return true;
        }

        // Backwards compatibility: If plan was created prior to input & settings inclusion,
        // and has other standard interactive/hotel menus enabled, automatically grant input & settings
        if (in_array($menuId, ['input', 'settings'], true)) {
            if (in_array($menuId, (array) $this->allowed_menus, true)) {
                return true;
            }
            if (in_array('weather', (array) $this->allowed_menus, true) || in_array('hotel_info', (array) $this->allowed_menus, true)) {
                return true;
            }
        }

        return in_array($menuId, (array) $this->allowed_menus, true);
    }

    /**
     * Check if OTT / Applications feature is enabled in this plan.
     */
    public function hasOtt(): bool
    {
        if ($this->is_ott_enabled === false) {
            return false;
        }

        if ($this->allowed_menus !== null && !$this->hasMenu('apps')) {
            return false;
        }

        return true;
    }

    /**
     * Total count of available TV launcher menus.
     */
    public static function totalMenusCount(): int
    {
        return count(self::getAvailableMenusCatalog());
    }

    /**
     * Master catalog of features/menus available for plan configuration.
     *
     * @return array<string, array{name: string, icon: string, desc: string, group: string}>
     */
    public static function getAvailableMenusCatalog(): array
    {
        return [
            'hotel_info' => [
                'name' => 'Hotel Info',
                'icon' => 'fa-solid fa-hotel',
                'desc' => 'Overview, contact, emergency desk & description',
                'group' => 'Hotel Info & Amenities',
            ],
            'room_info' => [
                'name' => 'Room Info',
                'icon' => 'fa-solid fa-door-open',
                'desc' => 'Room number, amenities & specifications',
                'group' => 'Hotel Info & Amenities',
            ],
            'amenities' => [
                'name' => 'Amenities',
                'icon' => 'fa-solid fa-bell-concierge',
                'desc' => 'Dining, swimming pool, gym & facilities',
                'group' => 'Hotel Info & Amenities',
            ],
            'apps' => [
                'name' => 'Applications / OTT',
                'icon' => 'fa-solid fa-grid-2',
                'desc' => 'Streaming OTT apps and android app store launcher',
                'group' => 'Entertainment',
            ],
            'livetv' => [
                'name' => 'Live TV',
                'icon' => 'fa-solid fa-tv',
                'desc' => 'IPTV and live broadcast TV channels',
                'group' => 'Entertainment',
            ],
            'screen_cast' => [
                'name' => 'Screen Cast',
                'icon' => 'fa-solid fa-chromecast',
                'desc' => 'Guest mobile/laptop wireless display projection',
                'group' => 'Entertainment',
            ],
            'flights' => [
                'name' => 'Flights Radar',
                'icon' => 'fa-solid fa-plane-departure',
                'desc' => 'Real-time flight arrival & departure tracker',
                'group' => 'Interactive Services',
            ],
            'weather' => [
                'name' => 'Weather Forecast',
                'icon' => 'fa-solid fa-cloud-sun',
                'desc' => 'Live local temperature & 5-day weather report',
                'group' => 'Interactive Services',
            ],
            'ourcity' => [
                'name' => 'Our City Guide',
                'icon' => 'fa-solid fa-map-location-dot',
                'desc' => 'Local tourist spots, dining & nearby attractions',
                'group' => 'Interactive Services',
            ],
            'input' => [
                'name' => 'TV Input / HDMI',
                'icon' => 'fa-solid fa-right-to-bracket',
                'desc' => 'Switch TV HDMI ports, AV sources & external inputs',
                'group' => 'Interactive Services',
            ],
            'settings' => [
                'name' => 'TV Settings',
                'icon' => 'fa-solid fa-gear',
                'desc' => 'Display, volume, network & TV system settings',
                'group' => 'System & Controls',
            ],
            'language' => [
                'name' => 'Language Switcher',
                'icon' => 'fa-solid fa-language',
                'desc' => 'Multi-lingual TV interface localization',
                'group' => 'System & Controls',
            ],
        ];
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
        $ratePerTv = $this->billed_amount_per_tv; // Auto-scales: monthly rate or annual rate (monthly * 12)
        $subtotal = round($ratePerTv * $tvCount, 2);

        $tax = $this->tax;
        $taxType = $this->tax_type ?? 'exclusive';

        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            return array_merge([
                'tv_count' => $tvCount,
                'monthly_rate_per_tv' => $this->monthly_rate_per_tv,
                'annual_total_per_tv' => $this->annual_total_per_tv,
                'billed_amount_per_tv' => $this->billed_amount_per_tv,
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
            'monthly_rate_per_tv' => $this->monthly_rate_per_tv,
            'annual_total_per_tv' => $this->annual_total_per_tv,
            'billed_amount_per_tv' => $this->billed_amount_per_tv,
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
                        'package_name' => $platform->package_name,
                        'icon' => $platform->icon_url,
                        'icon_url' => $platform->icon_url,
                        'icon_name' => $platform->icon_name,
                        'icon_slug' => $platform->icon_slug,
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
