<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'mobile_number',
        'room_number',
        'check_in_datetime',
        'check_out_datetime',
    ];

    protected $casts = [
        'check_in_datetime' => 'datetime',
        'check_out_datetime' => 'datetime',
    ];

    /**
     * Get the hotel associated with the guest.
     */
    public function hotel()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_id');
    }
}
