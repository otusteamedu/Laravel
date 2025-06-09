<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


        $middleware->redirectUsersTo(fn (Request $request) => route('profile.edit'));
        //$middleware->redirectUsersTo(function (Request $request) {return redirect()->intended(route('profile.edit'));} );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
