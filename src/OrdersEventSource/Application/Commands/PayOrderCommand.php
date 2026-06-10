<?php
namespace Src\OrdersEventSource\Application\Commands;

class PayOrderCommand
{
    public function __construct(
        public readonly int $orderId
    ) {}
}