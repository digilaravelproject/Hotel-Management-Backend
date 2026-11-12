<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HotelAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'hotel_admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'distributor_id',
        'owner_name',
        'email',
        'password',
        'phone',
        'hotel_name',
        'hotel_location',
        'city',
        'primary_airport_id',
        'secondary_airport_id',
        'hotel_logo',
        'hotel_image',
        'room_count',
        'plan_id',
        'selected_theme_id',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'license_key',
        'approval_status',
        'status',
        'otp_code',
        'otp_expires_at',
        'description',
        'emergency_contacts',
        'hotel_amenities',
        'slider_images',
        'hotel_gallery_images',
        'purchase_date',
        'expiry_date',
        'auto_renew',
        'renewal_type',
        'global_ott_settings',
        'global_menu_settings',
        'google2fa_secret',
        'google2fa_enabled',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
        'two_factor_recovery_codes',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'status' => 'boolean',
        'room_count' => 'integer',
        'selected_theme_id' => 'integer',
        'primary_airport_id' => 'integer',
        'secondary_airport_id' => 'integer',
        'emergency_contacts' => 'array',
        'hotel_amenities' => 'array',
        'slider_images' => 'array',
        'hotel_gallery_images' => 'array',
        'purchase_date' => 'datetime',
        'expiry_date' => 'datetime',
        'auto_renew' => 'boolean',
        'renewal_type' => 'string',
        'global_ott_settings' => 'array',
        'global_menu_settings' => 'array',
        'google2fa_enabled' => 'boolean',
        'google2fa_secret' => 'encrypted',
        'two_factor_recovery_codes' => 'encrypted:array',
    ];

    /**
     * Get the plan associated with the hotel admin.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Get the primary airport for the hotel.
     */
    public function primaryAirport()
    {
        return $this->belongsTo(Airport::class, 'primary_airport_id');
    }

    /**
     * Get the secondary airport for the hotel.
     */
    public function secondaryAirport()
    {
        return $this->belongsTo(Airport::class, 'secondary_airport_id');
    }

    /**
     * Get the connected devices for this hotel.
     */
    public function connectedDevices()
    {
        return $this->hasMany(ConnectedDevice::class, 'hotel_admin_id');
    }

    /**
     * Get the billing tax invoices issued for this hotel.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'hotel_admin_id');
    }

    /**
     * Get the allowed device limit (room_count).
     * Returns 0 if hotel has no active plan, unpaid status, or has expired.
     */
    public function getAllowedDeviceLimitAttribute()
    {
        if (!$this->plan_id || $this->payment_status !== 'paid') {
            return 0;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return 0;
        }

        return (int) ($this->room_count ?? 0);
    }

    /**
     * Determine computed subscription status.
     * Returns: 'expired', 'expiring_soon', 'active', 'unpaid', 'pending'
     */
    public function getSubscriptionStatusAttribute(): string
    {
        if ($this->payment_status === 'pending') {
            return 'pending';
        }

        if ($this->payment_status !== 'paid' || !$this->plan_id) {
            return 'unpaid';
        }

        if ($this->expiry_date) {
            if ($this->expiry_date->isPast()) {
                return 'expired';
            }
            $daysLeft = (int) ceil(now()->diffInHours($this->expiry_date) / 24);
            if ($daysLeft <= 4) {
                return 'expiring_soon';
            }
        }

        return 'active';
    }

    /**
     * Check if subscription has expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if ($this->payment_status !== 'paid') {
            return true;
        }

        return (bool) ($this->expiry_date && $this->expiry_date->isPast());
    }

    /**
     * Check if subscription is expiring soon (within 3-4 days).
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        if ($this->payment_status !== 'paid' || !$this->expiry_date) {
            return false;
        }

        if ($this->expiry_date->isPast()) {
            return false;
        }

        $daysLeft = (int) ceil(now()->diffInHours($this->expiry_date) / 24);
        return $daysLeft <= 4 && $daysLeft >= 0;
    }

    /**
     * Get rich presentation meta for subscription status badge.
     */
    public function getSubscriptionBadgeAttribute(): array
    {
        $status = $this->subscription_status;

        switch ($status) {
            case 'expired':
                return [
                    'key' => 'expired',
                    'label' => 'Expired',
                    'subtext' => $this->expiry_date ? 'Expired ' . $this->expiry_date->diffForHumans() : 'Expired',
                    'date_text' => $this->expiry_date ? $this->expiry_date->format('d M Y') : null,
                    'badge_class' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'dot_class' => 'bg-rose-500',
                    'icon' => 'fa-solid fa-triangle-exclamation',
                ];
            case 'expiring_soon':
                $days = max(1, (int) ceil(now()->diffInHours($this->expiry_date) / 24));
                return [
                    'key' => 'expiring_soon',
                    'label' => 'Expires Soon',
                    'subtext' => "In {$days} day" . ($days > 1 ? 's' : ''),
                    'date_text' => $this->expiry_date ? $this->expiry_date->format('d M Y') : null,
                    'badge_class' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'dot_class' => 'bg-amber-500',
                    'icon' => 'fa-solid fa-clock-rotate-left',
                ];
            case 'pending':
                return [
                    'key' => 'pending',
                    'label' => 'Pending',
                    'subtext' => 'Awaiting payment',
                    'date_text' => null,
                    'badge_class' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'dot_class' => 'bg-amber-400',
                    'icon' => 'fa-solid fa-hourglass-half',
                ];
            case 'unpaid':
                return [
                    'key' => 'unpaid',
                    'label' => 'Unpaid',
                    'subtext' => 'No active plan',
                    'date_text' => null,
                    'badge_class' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'dot_class' => 'bg-rose-500',
                    'icon' => 'fa-solid fa-circle-xmark',
                ];
            case 'active':
            default:
                return [
                    'key' => 'active',
                    'label' => 'Paid',
                    'subtext' => $this->expiry_date ? 'Valid till ' . $this->expiry_date->format('d M Y') : 'Active',
                    'date_text' => $this->expiry_date ? $this->expiry_date->format('d M Y') : 'Perpetual',
                    'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'dot_class' => 'bg-emerald-500',
                    'icon' => 'fa-solid fa-circle-check',
                ];
        }
    }

    /**
     * Get the distributor who registered this hotel.
     */
    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    /**
     * Get distributor sales records for this hotel.
     */
    public function distributorSales()
    {
        return $this->hasMany(DistributorSale::class, 'hotel_id');
    }
}
