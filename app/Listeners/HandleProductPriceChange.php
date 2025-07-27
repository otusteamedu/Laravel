<?php

namespace App\Listeners;

use App\Events\ProductPriceChanged;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleProductPriceChange implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductPriceChanged $event): void
    {
        // Отправляем job в очередь для рассылки уведомлений
        SendPriceChangeNotification::dispatch(
            $event->product,
            $event->oldPrice,
            $event->newPrice
        )->delay(now()->addMinutes(1)); // Задержка в 1 минуту для избежания спама
    }
}
