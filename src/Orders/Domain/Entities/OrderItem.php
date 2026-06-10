<?php
namespace Src\Orders\Domain\Entities;

use Src\Orders\Domain\ValueObjects\OrderPrice;
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