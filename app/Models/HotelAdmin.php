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
        'owner_name',
        'email',
        'password',
        'phone',
        'hotel_name',
        'hotel_location',
        'hotel_logo',
        'hotel_image',
        'room_count',
        'plan_id',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'license_key',
        'approval_status',
        'status',
        'otp_code',
        'otp_expires_at',
        'description',
        'slider_images',
        'purchase_date',
        'expiry_date',
        'global_ott_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        'slider_images' => 'array',
        'purchase_date' => 'datetime',
        'expiry_date' => 'datetime',
        'global_ott_settings' => 'array',
    ];

    /**
     * Get the plan associated with the hotel admin.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Get the connected devices for this hotel.
     */
    public function connectedDevices()
    {
        return $this->hasMany(ConnectedDevice::class, 'hotel_admin_id');
    }

    /**
     * Get the allowed device limit (room_count).
     */
    public function getAllowedDeviceLimitAttribute()
    {
        return $this->room_count;
    }
}
