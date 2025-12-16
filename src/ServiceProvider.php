<?php

namespace Vagrant\Ascii;

use Vagrant\Ascii\Services\AsciiRender;
use Vagrant\Ascii\Services\AsciiRenderInterface;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AsciiRenderInterface::class, AsciiRender::class);

    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ascii.php', 'ascii');

        $this->loadRoutesFrom(__DIR__ . "/../routes/web.php");
        $this->loadViewsFrom(__DIR__ . '/../views', 'ascii');

        $this->publishes([
            __DIR__ . '/../config/ascii.php' => config_path('ascii.php')
        ], 'ascii-config');

        $this->publishes([
            __DIR__ . '/../views' => base_path("resources/views/vendor/ascii")
        ], 'ascii-views');
    }
}