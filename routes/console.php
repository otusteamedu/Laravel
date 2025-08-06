<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Modules\ISS\src\Console\IssCache;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    echo 'schedule start MY testing c2' . PHP_EOL;
    echo base_path();
})->everyMinute();//->sendOutputTo(base_path().'/schedule.txt'); -- не работает в докере видимо пишет где то в контейнере

Schedule::command(IssCache::class, ['--clear', '-n'])->daily()->timezone('Europe/Moscow');
Schedule::command(IssCache::class, ['--start'])->dailyAt('00:20')->timezone('Europe/Moscow');



