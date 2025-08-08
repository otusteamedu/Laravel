<?php

namespace App\Listeners;

use App\Events\TariffCreated;
use App\Events\TariffUpdated;
use App\Events\TariffDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Tariff\Models\Tariff;

class SendTariffNotification implements ShouldQueue
{
    public function handle(object $event): void
    {
        $message = $this->buildMessage($event);

        if ($message) {
            Log::channel('telegram')->info($message);
        }
    }

    protected function buildMessage(object $event): ?string
    {
        if ($event instanceof TariffCreated) {
            $tariff = Tariff::find($event->tariffId);
            if (!$tariff) {
                return null;
            }
            return "Tariff created: {$tariff->name}";
        }

        if ($event instanceof TariffUpdated) {
            $tariff = Tariff::find($event->tariffId);
            if (!$tariff) {
                return null;
            }
            return "Tariff updated: {$tariff->name}";
        }

        if ($event instanceof TariffDeleted) {
            return "Tariff deleted: {$event->tariffName}";
        }

        return null;
    }
}
