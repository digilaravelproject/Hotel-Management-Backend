<?php

use App\Models\AppSetting;

if (!function_exists('app_setting')) {
    /**
     * Get a system setting value or the settings instance.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function app_setting(?string $key = null, mixed $default = null): mixed
    {
        try {
            $settings = AppSetting::getSettings();
            if ($key === null) {
                return $settings;
            }
            return $settings->{$key} ?? $default;
        } catch (\Throwable $e) {
            if ($key === 'app_name') return $default ?? 'DigiHotel';
            if ($key === 'footer_text') return $default ?? '© ' . date('Y') . ' DigiHotel Platform.';
            return $default;
        }
    }
}

if (!function_exists('app_name')) {
    /**
     * Get current application brand name.
     */
    function app_name(string $default = 'DigiHotel'): string
    {
        return (string) app_setting('app_name', $default);
    }
}

if (!function_exists('app_logo_url')) {
    /**
     * Get URL for current active application logo.
     */
    function app_logo_url(): string
    {
        try {
            return AppSetting::getSettings()->logo_url;
        } catch (\Throwable $e) {
            return asset('images/logo/logo.png');
        }
    }
}

if (!function_exists('app_favicon_url')) {
    /**
     * Get URL for current active application favicon.
     */
    function app_favicon_url(): string
    {
        try {
            return AppSetting::getSettings()->favicon_url;
        } catch (\Throwable $e) {
            return asset('favicon.png');
        }
    }
}
