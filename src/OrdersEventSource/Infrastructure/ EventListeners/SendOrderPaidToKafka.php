<?php
namespace Src\OrdersEventSource\Infrastructure\EventListeners;

use Src\OrdersEventSource\Domain\Events\OrderPaid;

class SendOrderPaidToKafka
{
    public function handle(
        OrderPaid $event
    ): void {
        //Отправка в kafka
    }
}