<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSetting extends Model
{
    protected $table = 'flight_settings';

    protected $fillable = [
        'provider',
        'api_key',
        'cache_ttl_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cache_ttl_minutes' => 'integer',
    ];

    /**
     * Get the active flight setting or default fallback.
     */
    public static function getActiveSetting(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
