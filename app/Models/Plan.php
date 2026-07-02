<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'room_count',
        'price',
        'status',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'room_count' => 'integer',
    ];

    /**
     * Get the hotel admins subscribed to this plan.
     */
    public function hotelAdmins()
    {
        return $this->hasMany(HotelAdmin::class, 'plan_id');
    }
}
