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
     * Get the pre-configured default list of available OTT platforms.
     *
     * @return array<int, array<string, string>>
     */
    public static function getAvailableOttPlatforms(): array
    {
        return [
            ['name' => 'Netflix', 'package' => 'com.netflix.mediaclient'],
            ['name' => 'Disney+ Hotstar', 'package' => 'in.startv.hotstar'],
            ['name' => 'Amazon Prime Video', 'package' => 'com.amazon.avod.thirdpartyclient'],
            ['name' => 'Zee5', 'package' => 'com.graymatrix.did'],
            ['name' => 'Sony LIV', 'package' => 'com.sony.liv'],
            ['name' => 'JioCinema', 'package' => 'com.jio.media.ondemand'],
            ['name' => 'Aha', 'package' => 'ka.alua.aha'],
            ['name' => 'Sun NXT', 'package' => 'com.suntv.sunnxt'],
            ['name' => 'MX Player', 'package' => 'com.mxtech.videoplayer.ad'],
            ['name' => 'Discovery+', 'package' => 'com.discovery.discoveryplus.mobile'],
            ['name' => 'ALTBalaji', 'package' => 'com.balaji.alt'],
            ['name' => 'Eros Now', 'package' => 'com.erosnow'],
            ['name' => 'Hungama Play', 'package' => 'com.hungama.myplay.activity'],
            ['name' => 'Hoichoi', 'package' => 'com.viewlift.hoichoi'],
            ['name' => 'Planet Marathi', 'package' => 'com.planetmarathi.ott'],
            ['name' => 'Chaupal', 'package' => 'com.chaupal.app'],
            ['name' => 'ManoramaMAX', 'package' => 'com.manoramamax.app'],
            ['name' => 'Voot', 'package' => 'com.tv.v18.viola'],
        ];
    }

    /**
     * Get the hotel admins subscribed to this plan.
     */
    public function hotelAdmins()
    {
        return $this->hasMany(HotelAdmin::class, 'plan_id');
    }
}
