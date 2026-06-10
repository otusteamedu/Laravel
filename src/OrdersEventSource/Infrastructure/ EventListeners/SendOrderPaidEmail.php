<?php
namespace Src\OrdersEventSource\Infrastructure\EventListeners;

use Src\OrdersEventSource\Domain\Events\OrderPaid;

class SendOrderPaidEmail
{
    public function handle(
        OrderPaid $event
    ): void {
        //Отправка email 
    }
}