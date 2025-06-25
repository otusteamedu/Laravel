<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\WarmCacheCommand;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(WarmCacheCommand::class, [
    'news'
])->everyFifteenMinutes();

Schedule::command(WarmCacheCommand::class, [
    'categories'
])->everyThirtyMinutes();
