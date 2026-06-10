<?php

//Order.php — это ядро бизнес-правил заказа.
//Он отвечает на вопрос:
//❓ “Что можно делать с заказом и при каких условиях?”

//Он НЕ отвечает на:

//* как сохранить
//* куда сохранить
//* как отправить email

//Он:
//* хранит состояние
//* защищает бизнес-правила
//* управляет связанными объектами (items)
//* гарантирует консистентность

namespace Src\Orders\Domain\Entities;

use Src\Orders\Domain\ValueObjects\OrderPrice;
use Src\Orders\Domain\ValueObjects\OrderId;
class Order
{
    private OrderId $id;
    //Почему paid можно не делать VO? 
    private bool $paid = false;

    //Почему OrderItem является сущностью, а не VO? 
    //Потому что VO не имеет жизненного цикла
    /** @var array<OrderItem> */
    private array $items = [];

    public function __construct()
    {
    }

    public function addItem(string $productId, int $qty, OrderPrice $price): void
    {
        if ($this->paid) {
            throw new \DomainException("Cannot modify paid order");
        }

        $this->items[] = new OrderItem($productId, $qty, $price);
    }

    public function pay(): void
    {
        if ($this->paid) {
            throw new \DomainException("Order already paid");
        }

        if (empty($this->items)) {
            throw new \DomainException("Cannot pay empty order");
        }

        $this->paid = true;
    }

    /**
    * @return array<OrderItem>
    */
    public function items(): array
    {
        return $this->items;
    }

    public function id(): OrderId
    {
        return $this->id;
    }
}
