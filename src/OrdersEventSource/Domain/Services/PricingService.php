<?php
namespace Src\OrdersEventSource\Domain\Services;

use Src\OrdersEventSource\Domain\Entities\Order;
use Src\OrdersEventSource\Domain\ValueObjects\OrderPrice;

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