<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Schedule::command('warmup:cache')
    ->daily()
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('clear:cache')
    ->daily()
    ->withoutOverlapping()
    ->onOneServer();

#Schedule::call(function () {
#    file_put_contents(storage_path('logs/scheduler_test.log'), now() . " - Scheduler ran\n", FILE_APPEND);
#})->everyMinute();
