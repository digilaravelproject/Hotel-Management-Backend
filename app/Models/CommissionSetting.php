<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CommissionSetting extends Model
{
    protected $table = 'commission_settings';

    protected $fillable = [
        'default_percentage',
    ];

    protected $casts = [
        'default_percentage' => 'decimal:2',
    ];

    public const CACHE_KEY = 'global_commission_settings';

    /**
     * Get active global commission setting (cached).
     */
    public static function getSetting(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $setting = static::first();
            if (!$setting) {
                $setting = static::create([
                    'default_percentage' => 10.00,
                ]);
            }
            return $setting;
        });
    }

    /**
     * Flush cached settings.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
