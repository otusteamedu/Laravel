<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/log', function (Request $request) {
    // Ставим логи разных уровней и проверяем, чтоб в телеграм приходило только то, что не ниже уровня error

    Log::channel('telegram')->debug('debug level error');
    Log::channel('telegram')->info('info level error');
    Log::channel('telegram')->error('error level error');
    Log::channel('telegram')->critical('critical level error');
    Log::channel('telegram')->emergency('emergency level error');

    return ['ok' => true];
});
