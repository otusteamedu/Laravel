<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\DB;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Задача: добавлем каждую минуту по одной записи в таблицу puples
        $schedule->call(function () {
            DB::table('puples')->insert(
                [
                    [
                        'name' => fake('ru_RU')->firstName,
                        'surname' => fake('ru_RU')->lastName,
                        'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                        'gender' => fake()->randomElement(['male', 'female']),
                        'email' => fake()->email,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                ]
            );

        })->everyMinute(); // Выполнять задачу ежеминутно
    })
    ->create();
