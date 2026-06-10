<?php
namespace Src\Orders\Application\Handlers;

use RuntimeException;
use Src\Orders\Application\Commands\PayOrderCommand;
use Src\Orders\Domain\Repositories\OrderRepository;
use Src\Orders\Domain\ValueObjects\OrderId;

class PayOrderHandler
{
    public function __construct(
        private OrderRepository $orders
    ) {}

    public function handle(
        PayOrderCommand $command
    ): void {
        $order = $this->orders->find(
            new OrderId($command->orderId)
        );

        if (!$order) {
            throw new RuntimeException('Order not found');
        }

        $order->pay();

        $this->orders->save($order);
    }
}