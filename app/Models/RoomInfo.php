<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomInfo extends Model
{
    use HasFactory;

    protected $table = 'room_infos';

    protected $fillable = [
        'hotel_admin_id',
        'sr_no',
        'title',
        'image',
        'description',
        'specifications',
        'status',
    ];

    protected $casts = [
        'sr_no' => 'integer',
        'specifications' => 'array',
        'status' => 'boolean',
    ];

    public function hotelAdmin()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
