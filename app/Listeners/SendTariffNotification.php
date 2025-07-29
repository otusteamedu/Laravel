<?php

namespace App\Listeners;

use App\Events\TariffCreated;
use App\Events\TariffUpdated;
use App\Events\TariffDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTariffNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {

        //Log::info('SendTariffNotification handle() triggered for event: ' . get_class($event));

        $message = $this->buildMessage($event);

        if ($message) {
            Log::channel('telegram')->info($message);
        }
    }

    /**
     * Build Telegram message based on event type.
     *
     * @param object $event
     * @return string|null
     */
    protected function buildMessage(object $event): ?string
    {
        if ($event instanceof TariffCreated) {
            return "Tariff created: {$event->tariff->name}";
        }

        if ($event instanceof TariffUpdated) {
            return "Tariff updated: {$event->tariff->name}";
        }

        if ($event instanceof TariffDeleted) {
            return "Tariff deleted: {$event->tariffName}";
        }

        return null;
    }
}
