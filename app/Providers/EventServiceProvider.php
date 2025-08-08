<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TariffCreated;
use App\Events\TariffUpdated;
use App\Events\TariffDeleted;
use App\Listeners\SendTariffNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TariffCreated::class => [
            SendTariffNotification::class,
        ],
        TariffUpdated::class => [
            SendTariffNotification::class,
        ],
        TariffDeleted::class => [
            SendTariffNotification::class,
        ],
    ];
}
