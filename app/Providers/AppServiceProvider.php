<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\TvConfigUpdatedEvent::class,
            \App\Listeners\SendFcmTvSyncNotification::class
        );

        \App\Models\HotelAdmin::observe(\App\Observers\HotelAdminObserver::class);
        \App\Models\Amenity::observe(\App\Observers\AmenityObserver::class);
        \App\Models\Guest::observe(\App\Observers\GuestObserver::class);
        \App\Models\ConnectedDevice::observe(\App\Observers\ConnectedDeviceObserver::class);
        \App\Models\TvTemplate::observe(\App\Observers\TvTemplateObserver::class);
        \App\Models\Plan::observe(\App\Observers\PlanObserver::class);
        \App\Models\OttPlatform::observe(\App\Observers\OttPlatformObserver::class);

        // Strict Change Detection Observer
        \App\Models\HotelAdmin::observe(\App\Observers\TvConfigObserver::class);
        \App\Models\Amenity::observe(\App\Observers\TvConfigObserver::class);
        \App\Models\RoomInfo::observe(\App\Observers\TvConfigObserver::class);
        \App\Models\Guest::observe(\App\Observers\TvConfigObserver::class);
        \App\Models\TvTemplate::observe(\App\Observers\TvConfigObserver::class);
    }
}
