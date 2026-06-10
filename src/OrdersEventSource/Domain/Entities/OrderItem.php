<?php
namespace Src\OrdersEventSource\Domain\Entities;

use Src\OrdersEventSource\Domain\ValueObjects\OrderPrice;
class OrderItem
{
    public function __construct(
        private string $productId,
        private int $qty,
        private OrderPrice $price
    ) {}

    public function subtotal(): int
    {
        return $this->qty * $this->price->value();
    }
}