<?php

namespace Src\OrdersEventSource\Domain\Events;

use Src\OrdersEventSource\Domain\ValueObjects\OrderPrice;

class OrderItemAdded
{
    public function __construct(
        public readonly string $productId,
        public readonly int $qty,
        public readonly OrderPrice $price,
    ) {}
}