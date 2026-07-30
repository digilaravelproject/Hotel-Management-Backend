<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OttPlatform;

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
        'ott_platforms',
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
        'ott_platforms' => 'array',
    ];

    /**
     * Get the list of available OTT platforms dynamically from database master table.
     *
     * @return array<int, array<string, string>>
     */
    public static function getAvailableOttPlatforms(): array
    {
        return OttPlatform::query()
            ->where('status', true)
            ->orderBy('id', 'asc')
            ->get(['name', 'package_name as package', 'icon'])
            ->toArray();
    }

    /**
     * Get the hotel admins subscribed to this plan.
     */
    public function hotelAdmins()
    {
        return $this->hasMany(HotelAdmin::class, 'plan_id');
    }
}
