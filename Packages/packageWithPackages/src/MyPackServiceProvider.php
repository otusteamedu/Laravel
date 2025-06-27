<?php

namespace My\PackageWithPackages;

use My\PackageWithPackages\Console\InstallCommand;
use My\PackageWithPackages\Services\PackageList\PackageListRepoInterface;
use My\PackageWithPackages\Repositories\PackageListRepo;

class MyPackServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(PackageListRepoInterface::class, PackageListRepo::class);
    }

    public function boot()
    {

        //ставлю здесь, потому что этот код выполняется перед загрузкой приложения,
        // поэтому нет проблем с очередностью подключения источников
        // кроме того рагистрация главных ресурсов пакета отделена от остальных операций из boot
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'packageWithPackages');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'packageWithPackages');
        $this->mergeConfigFrom(__DIR__.'/../config/package.php', 'packageWithPackages');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        //публикация конфигов
        $this->publishes(
            [
                __DIR__.'/../config/package.php' => config_path('package.php'),
            ],
            'config'
        );
        //публикация стилей
        $this->publishes(
            [
                __DIR__.'/../public/css' => public_path('css/package'),
            ], 'css'
        );

        //подключаем классы команд
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

    }
}
