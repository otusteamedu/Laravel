<?php

namespace App\Providers;

use App\Services\NotificationService\LogNotificationService;
use App\Services\NotificationService\EmailNotificationService;
use App\Services\NotificationService\MultiNotificationService;
use App\Services\NotificationService\MultiNotificationServiceInterface;
use App\Services\NotificationService\NotificationServiceInterface;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(NotificationServiceInterface::class, MultiNotificationService::class);

        $this->app->tag([LogNotificationService::class], 'notifiers');

        $this->app->when(MultiNotificationService::class)
            ->needs('$notificationServices')
            ->giveTagged('notifiers');

        $this->app
            ->when(EmailNotificationService::class)
            ->needs('$fromEmail')
            ->give('admin@localhost');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
