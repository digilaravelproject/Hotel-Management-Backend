<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'app_name',
        'app_short_name',
        'app_logo',
        'app_favicon',
        'footer_text',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Cache key for global app settings.
     */
    public const CACHE_KEY = 'global_app_settings_record';

    /**
     * Retrieve the cached active settings instance or initialize defaults.
     */
    public static function getSettings(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $setting = static::first();
            if (!$setting) {
                $setting = static::create([
                    'app_name' => 'DigiHotel',
                    'app_short_name' => 'DigiHotel',
                    'app_logo' => null,
                    'app_favicon' => null,
                    'footer_text' => '© ' . date('Y') . ' DigiHotel Platform. All rights reserved.',
                    'contact_email' => 'support@digihotel.com',
                    'contact_phone' => '+91 9876543210',
                ]);
            }
            return $setting;
        });
    }

    /**
     * Flush settings cache on changes.
     */
    public static function flushSettingsCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get computed URL for application logo.
     */
    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->app_logo)) {
            $path = public_path($this->app_logo);
            if (file_exists($path)) {
                return asset($this->app_logo);
            }
        }

        if (file_exists(public_path('images/logo/logo.png'))) {
            return asset('images/logo/logo.png');
        }

        return asset('images/logo/logo.png');
    }

    /**
     * Get computed URL for application favicon.
     */
    public function getFaviconUrlAttribute(): string
    {
        if (!empty($this->app_favicon)) {
            $path = public_path($this->app_favicon);
            if (file_exists($path)) {
                return asset($this->app_favicon);
            }
        }

        if (file_exists(public_path('favicon.png'))) {
            return asset('favicon.png');
        }

        if (file_exists(public_path('favicon.ico'))) {
            return asset('favicon.ico');
        }

        return asset('favicon.png');
    }
}
