<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurCity extends Model
{
    use HasFactory;

    protected $table = 'our_cities';

    protected $fillable = [
        'hotel_admin_id',
        'sr_no',
        'title',
        'image',
        'description',
        'attractions',
        'status',
    ];

    protected $casts = [
        'sr_no' => 'integer',
        'attractions' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Relationship to the HotelAdmin.
     */
    public function hotelAdmin()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
