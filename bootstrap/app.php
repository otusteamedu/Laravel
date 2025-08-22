<?php

use App\Infrastructure\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        AppServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Interfaces\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
