<?php
namespace Src\OrdersEventSource\Domain\Events;

use Src\OrdersEventSource\Domain\ValueObjects\OrderId;

class OrderPaid
{
    public function __construct(
        public readonly OrderId $orderId
    ) {}
}