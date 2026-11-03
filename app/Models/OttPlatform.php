<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OttPlatform extends Model
{
    protected $table = 'ott_platforms';

    protected $fillable = [
        'name',
        'package_name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = [
        'icon_url',
    ];

    /**
     * Package name to default SVG icon mapping
     */
    public static array $packageIconMap = [
        'com.google.android.youtube.tv' => 'images/ott/youtube.svg',
        'com.google.android.youtube' => 'images/ott/youtube.svg',
        'com.android.vending' => 'images/ott/google-play.svg',
        'com.netflix.mediaclient' => 'images/ott/netflix.svg',
        'in.startv.hotstar' => 'images/ott/hotstar.svg',
        'com.amazon.avod.thirdpartyclient' => 'images/ott/prime-video.svg',
        'com.graymatrix.did' => 'images/ott/zee5.svg',
        'com.sony.liv' => 'images/ott/sonyliv.svg',
        'com.jio.media.ondemand' => 'images/ott/jiocinema.svg',
        'ka.alua.aha' => 'images/ott/aha.svg',
        'com.suntv.sunnxt' => 'images/ott/sun-nxt.svg',
        'com.mxtech.videoplayer.ad' => 'images/ott/mx-player.svg',
        'com.discovery.discoveryplus.mobile' => 'images/ott/discovery-plus.svg',
        'com.balaji.alt' => 'images/ott/altbalaji.svg',
        'com.erosnow' => 'images/ott/eros-now.svg',
        'com.hungama.myplay.activity' => 'images/ott/hungama.svg',
        'com.viewlift.hoichoi' => 'images/ott/hoichoi.svg',
        'com.planetmarathi.ott' => 'images/ott/planet-marathi.svg',
        'com.chaupal.app' => 'images/ott/chaupal.svg',
        'com.manoramamax.app' => 'images/ott/manoramamax.svg',
        'com.tv.v18.viola' => 'images/ott/voot.svg',
    ];

    /**
     * Always resolve a valid icon URL, fallback gracefully to default official SVG
     */
    public function getIconUrlAttribute(): string
    {
        if (!empty($this->icon)) {
            if (str_starts_with($this->icon, 'http://') || str_starts_with($this->icon, 'https://')) {
                return $this->icon;
            }
            return asset(ltrim($this->icon, '/'));
        }

        if (!empty($this->package_name) && isset(self::$packageIconMap[$this->package_name])) {
            return asset(self::$packageIconMap[$this->package_name]);
        }

        $slug = Str::slug($this->name);
        if (file_exists(public_path('images/ott/' . $slug . '.svg'))) {
            return asset('images/ott/' . $slug . '.svg');
        }

        return '';
    }
}
