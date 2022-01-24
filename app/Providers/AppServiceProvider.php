<?php

namespace App\Providers;

use App\Interfaces\IAuthService;
use App\Interfaces\IExchangeService;
use App\Services\AuthService;
use App\Services\ExchangeService;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IExchangeService::class, ExchangeService::class);
    }
}
