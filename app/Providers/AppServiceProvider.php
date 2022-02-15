<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\Salon\SalonRepositoryInterface::class,
            \App\Repositories\Salon\SalonRepository::class
        );
        $this->app->singleton(
            \App\Repositories\Booking\BookingRepositoryInterface::class,
            \App\Repositories\Booking\BookingRepository::class
        );
        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );
        // $this->app->bind(
        //     'App\Repositories\Salon\SalonRepositoryInterface',
        //     'App\Repositories\Salon\SalonRepository'
        // );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
