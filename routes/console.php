<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ежедневный сброс кэша в 2:00 ночи
Schedule::command('cache:clear-app --all')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping(5) // Не запускать, если предыдущая задача еще выполняется
    ->runInBackground()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Ежедневный сброс кэша выполнен успешно');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Ошибка при ежедневном сбросе кэша');
    });

// Прогрев кэша после сброса в 2:30 ночи
Schedule::command('cache:warmup --all')
    ->daily()
    ->at('02:30')
    ->withoutOverlapping(10) // Не запускать, если предыдущая задача еще выполняется
    ->runInBackground()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Ежедневный прогрев кэша выполнен успешно');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Ошибка при ежедневном прогреве кэша');
    });

// Прогрев популярных данных каждые 4 часа
Schedule::command('cache:warmup --components=tasks,categories')
    ->cron('0 */4 * * *') // Каждые 4 часа
    ->withoutOverlapping(5)
    ->runInBackground()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Периодический прогрев кэша выполнен успешно');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Ошибка при периодическом прогреве кэша');
    });

// Очистка устаревших токенов Passport каждые 24 часа в 3:00
Schedule::command('passport:purge')
    ->daily()
    ->at('03:00')
    ->withoutOverlapping(5)
    ->runInBackground()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Очистка токенов Passport выполнена успешно');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Ошибка при очистке токенов Passport');
    });
