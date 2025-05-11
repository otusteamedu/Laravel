<?php


namespace App\Modules\ISS;

use Illuminate\Support\ServiceProvider;

class IssServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //подключаем шаблоны модуля
        $this->loadViewsFrom(__DIR__.'/resources/views', 'iss');
        //подключаем конфиг модуля
        $this->mergeConfigFrom(__DIR__.'/config/iss.php', 'iss');
        //подключаем локализацию
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'iss');
        //подключаем маршруты
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__.'/config/iss.php' => config_path('iss.php'),
            ],
            'config'
        );
        $this->publishes(
            [
                __DIR__.'/resources/css/issMainPageStyle.css' => base_path('resources/css/issMainPageStyle.css'),
                __DIR__.'/resources/css/issUserPageStyle.css' => base_path('resources/css/issMainPageStyle.css'),
            ],
            'style'
        );
        $this->publishes(
            [__DIR__.'/public/images/' => public_path('images/iss')],
            'public'
        );
    }
}
