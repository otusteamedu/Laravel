<?php
namespace Src\Orders\Domain\Repositories;

use Src\Orders\Domain\Entities\Order;
use Src\Orders\Domain\ValueObjects\OrderId;

interface OrderRepository
{
    public function save(Order $order): void;

    public function find(OrderId $id): ?Order;
}
?>