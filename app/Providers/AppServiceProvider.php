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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\HotelAdmin::observe(\App\Observers\HotelAdminObserver::class);
        \App\Models\Amenity::observe(\App\Observers\AmenityObserver::class);
        \App\Models\Guest::observe(\App\Observers\GuestObserver::class);
        \App\Models\ConnectedDevice::observe(\App\Observers\ConnectedDeviceObserver::class);
        \App\Models\TvTemplate::observe(\App\Observers\TvTemplateObserver::class);
        \App\Models\Plan::observe(\App\Observers\PlanObserver::class);
        \App\Models\OttPlatform::observe(\App\Observers\OttPlatformObserver::class);
    }
}
