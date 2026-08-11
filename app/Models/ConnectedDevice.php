<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectedDevice extends Model
{
    protected $table = 'connected_devices';

    protected $fillable = [
        'hotel_admin_id',
        'room_no',
        'device_id',
        'mac_address',
        'ip_address',
        'model',
        'brand',
        'os_version',
        'api_token',
        'fcm_token',
        'ott_overrides',
        'menu_overrides',
    ];

    protected $casts = [
        'ott_overrides' => 'array',
        'menu_overrides' => 'array',
    ];

    /**
     * Get the hotel admin associated with the device.
     */
    public function hotelAdmin()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
