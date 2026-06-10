<?php

namespace Src\OrdersEventSource\Domain\Events;

use Src\OrdersEventSource\Domain\ValueObjects\OrderId;

class OrderCreated
{
    public function __construct(
        public readonly OrderId $orderId
    ) {}
}