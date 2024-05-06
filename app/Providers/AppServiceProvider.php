<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Ride;
use App\Observers\RideObserver;

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
        Ride::observe(RideObserver::class);
    }
}
