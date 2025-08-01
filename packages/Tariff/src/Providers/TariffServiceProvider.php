<?php

namespace Tariff\Providers;

use Illuminate\Support\ServiceProvider;

class TariffServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tariff.php', 'tariff');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tariff');

        $this->publishes([
            __DIR__ . '/../config/tariff.php' => config_path('tariff.php'),
        ], 'tariff-config');
    }

}
