<?php
namespace Src\Orders\Domain\Services;

use Src\Orders\Domain\Entities\Order;
use Src\Orders\Domain\ValueObjects\OrderPrice;

class PricingService
{
    public function calculate(Order $order): OrderPrice
    {
        $total = new OrderPrice(0, '$');

        foreach ($order->items() as $item) {
            $price = new OrderPrice($item->subtotal(), '$');
            $total = $total->add($price);
        }

        return $total;
    }
}