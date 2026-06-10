<?php
namespace Src\Orders\Application\Handlers;

use Src\Orders\Application\Queries\GetOrderQuery;
use Src\Orders\Domain\Entities\Order;
use Src\Orders\Domain\Repositories\OrderRepository;
use Src\Orders\Domain\ValueObjects\OrderId;

class GetOrderHandler
{
    public function __construct(
        private OrderRepository $orders
    ) {}

    public function handle(
        GetOrderQuery $query
    ): Order {
        return $this->orders->find(
            new OrderId($query->orderId)
        );
    }
}