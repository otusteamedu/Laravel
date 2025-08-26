<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'role:admin,editor'])
                 ->prefix('admin_panel')
                 ->name('admin.')
                 ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(
            [
                'locale' => SetLocaleMiddleware::class,
                'role' => RoleMiddleware::class
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
