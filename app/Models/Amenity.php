<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $table = 'amenities';

    protected $fillable = [
        'hotel_admin_id',
        'name',
        'icon',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the hotel admin that owns this amenity.
     */
    public function hotel()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
