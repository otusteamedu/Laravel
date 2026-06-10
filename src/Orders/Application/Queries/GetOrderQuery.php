<?php
namespace Src\Orders\Application\Queries;

class GetOrderQuery
{
    public function __construct(
        public readonly int $orderId
    ) {}
}