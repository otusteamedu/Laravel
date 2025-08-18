<?php

namespace Konstantin\Calc;



class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/calc.php', "calc");
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', "calc");

        $this->publishes([
            __DIR__ . '/../config/calc.php' => config_path("calc.php")
        ], "calc-config");

        $this->publishes([
            __DIR__ . '/../views' => resource_path("views/vendor/calc")
        ], "calc-views");
    }
}