<?php

namespace App\Providers;

use App\Service\IReservationsAdmin;
use App\Service\IReservationService;
use App\Service\IReservationsExpired;
use App\Service\ReservationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            IReservationService::class,
            ReservationService::class
        );
        $this->app->bind(
            IReservationsExpired::class,
            ReservationService::class
        );
        $this->app->bind(
            IReservationsAdmin::class,
            ReservationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
