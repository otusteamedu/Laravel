<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'admin'])
                 ->prefix('admin_panel')
                 ->name('admin.')
                 ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias(
            [
               'check_admin' => AdminMiddleware::class,
               'locale' => SetLocaleMiddleware::class,
            ]
        );

        $middleware->appendToGroup('admin', [
            'auth',
            'check_admin',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
