<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvPairSession extends Model
{
    protected $table = 'tv_pair_sessions';

    protected $fillable = [
        'pair_code',
        'device_id',
        'mac_address',
        'ip_address',
        'model',
        'brand',
        'os_version',
        'status',
        'hotel_admin_id',
        'assigned_room_no',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the session is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    /**
     * Get associated hotel admin.
     */
    public function hotelAdmin()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
