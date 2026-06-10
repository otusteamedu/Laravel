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

namespace Src\OrdersEventSource\Domain\Entities;

use Src\OrdersEventSource\Domain\Events\OrderCreated;
use Src\OrdersEventSource\Domain\Events\OrderItemAdded;
use Src\OrdersEventSource\Domain\Events\OrderPaid;
use Src\OrdersEventSource\Domain\ValueObjects\OrderPrice;
use Src\OrdersEventSource\Domain\ValueObjects\OrderId;
class Order
{
    private OrderId $id;

    //Почему paid можно не делать VO? 
    private bool $paid = false;

    //Почему OrderItem является сущностью, а не VO? 
    //Потому что VO не имеет жизненного цикла
    /** @var array<OrderItem> */
    private array $items = [];
    private array $events = [];

    public function __construct()
    {
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    /**
    * @return array<OrderItem>
    */
    public function items(): array
    {
        return $this->items;
    }

    // восстановление из истории
    public static function reconstitute(array $events): self
    {
        $order = new self();

        foreach ($events as $event) {
            $order->apply($event);
        }

        return $order;
    }

    public function addItem(string $productId, int $qty, OrderPrice $price): void
    {
        $this->recordThat(
            new OrderItemAdded($productId, $qty, $price)
        );
    }

    public function pay(): void
    {
        if ($this->paid) {
            throw new \DomainException("Order already paid");
        }

        if (empty($this->items)) {
            throw new \DomainException("Cannot pay empty order");
        }

        $this->recordThat(
            new OrderPaid($this->id)
        );
    }

    private function recordThat(object $event): void
    {
        $this->apply($event);
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    private function apply(object $event): void
    {
        match (true) {
            //Тут все ок, на уроке не надо было исправлять 
            //В случаи отмены оплаты будет другой ивент
            $event instanceof OrderPaid =>
                $this->paid = true,

            $event instanceof OrderCreated =>
                $this->id = $event->orderId,
            
            $event instanceof OrderItemAdded => 
                $this->items[] = new OrderItem(
                    $event->productId,
                    $event->qty,
                    new OrderPrice((int) $event->price, '$')
                ),

            default => null
        };
    }
}
