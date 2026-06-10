<?php
namespace Src\Orders\Application\Commands;

class PayOrderCommand
{
    public function __construct(
        public readonly int $orderId
    ) {}
}