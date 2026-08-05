<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TvVersionCacheService
{
    /**
     * Cache key prefix for version check.
     */
    protected const CACHE_PREFIX = 'tv_check_version_hotel_';

    /**
     * Generate hotel specific cache key.
     */
    public static function getCacheKey(int $hotelId, int $deviceId, ?string $clientVersion): string
    {
        $versionTag = $clientVersion ?? 'none';
        $globalStamp = Cache::get('tv_global_template_version_stamp', 1);
        return self::CACHE_PREFIX . "{$hotelId}_device_{$deviceId}_v_{$versionTag}_g_{$globalStamp}";
    }

    /**
     * Track and store cache key for a given hotel so it can be selectively flushed.
     */
    public static function rememberCheckVersion(int $hotelId, int $deviceId, ?string $clientVersion, \Closure $callback)
    {
        $cacheKey = self::getCacheKey($hotelId, $deviceId, $clientVersion);
        
        // Track key under hotel index list
        self::trackHotelKey($hotelId, $cacheKey);

        // Remember forever until explicit model update invalidates the hotel cache
        return Cache::rememberForever($cacheKey, $callback);
    }

    /**
     * Track all cache keys belonging to a specific hotel.
     */
    protected static function trackHotelKey(int $hotelId, string $cacheKey): void
    {
        $indexKey = "tv_cache_index_hotel_{$hotelId}";
        $trackedKeys = Cache::get($indexKey, []);

        if (!in_array($cacheKey, $trackedKeys, true)) {
            $trackedKeys[] = $cacheKey;
            Cache::forever($indexKey, $trackedKeys);
        }
    }

    /**
     * Clear all check-version cache keys ONLY for the specified hotel.
     */
    public static function clearHotelCache(int $hotelId): void
    {
        $indexKey = "tv_cache_index_hotel_{$hotelId}";
        $trackedKeys = Cache::get($indexKey, []);

        foreach ($trackedKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget($indexKey);
    }

    /**
     * Clear check-version cache for all hotels (e.g., on global TvTemplate change).
     */
    public static function clearAllHotelsCache(): void
    {
        // Increment global template version stamp so all previous cache keys instantly become invalid for all hotels
        if (!Cache::has('tv_global_template_version_stamp')) {
            Cache::forever('tv_global_template_version_stamp', 2);
        } else {
            Cache::increment('tv_global_template_version_stamp');
        }
    }
}
