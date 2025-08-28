<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(HandleGuestCart::class);
    })
    ->withSchedule(function (Schedule $schedule) {

        // Ежедневная очистка корзин в 2:00 ночи
        $schedule->command('carts:cleanup --days=30')
            ->dailyAt('02:00')
            ->onOneServer()
            ->appendOutputTo(storage_path('logs/cart-cleanup.log'));

        // Еженедельная проверка по понедельникам в 1:00
        $schedule->command('carts:check --days=30')
            ->weeklyOn(1, '01:00')
            ->appendOutputTo(storage_path('logs/cart-check.log'));

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
