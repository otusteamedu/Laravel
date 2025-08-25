<?php

namespace App\Interfaces\Console;

use App\Interfaces\Console\Commands\ClearCache;
use App\Interfaces\Console\Commands\WarmupCache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Определяет планировщик задач приложения.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ClearCache::class)
            ->daily()
            ->onOneServer()
            ->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command(WarmupCache::class)
            ->daily()
            ->onOneServer()
            ->appendOutputTo(storage_path('logs/schedule.log'));
    }
}
