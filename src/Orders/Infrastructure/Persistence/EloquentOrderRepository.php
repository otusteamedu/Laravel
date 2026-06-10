<?php

namespace Src\Orders\Infrastructure\Persistence;

use Src\Orders\Domain\Entities\Order;
use Src\Orders\Domain\Repositories\OrderRepository;
use Src\Orders\Domain\ValueObjects\OrderId;
use App\Models\Order as OrderModel;

class EloquentOrderRepository implements OrderRepository
{
    public function find(
        OrderId $id
    ): ?Order {

        $model = OrderModel::find(
            $id->value()
        );

        if (!$model) {
            return null;
        }

        return (new Order($id));
    }

    public function save(
        Order $order
    ): void {

        OrderModel::updateOrCreate(
            ['id' => $order->id()->value()],
            [
                'status' => ""//$order->status(),
            ],
        );
    }
}