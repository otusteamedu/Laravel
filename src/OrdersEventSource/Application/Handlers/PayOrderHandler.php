<?php
namespace Src\OrdersEventSource\Application\Handlers;

use Src\OrdersEventSource\Application\Commands\PayOrderCommand;
use Src\OrdersEventSource\Domain\Contracts\EventStore;
use Src\OrdersEventSource\Domain\Entities\Order;

class PayOrderHandler
{
    public function __construct(
        private EventStore $eventStore
    ) {}

    public function handle(PayOrderCommand $command): void
    {
        // 1. загрузить события
        $events = $this->eventStore->load($command->orderId);

        // 2. восстановить агрегат
        $order = Order::reconstitute($events);

        // 3. бизнес-логика
        $order->pay();

        $newEvents = $order->releaseEvents();

        // 4. сохранить новые события
        $this->eventStore->append(
            $command->orderId,
            $newEvents
        );

        //Event Drive
        foreach($newEvents as $event) {
            event($event);
        }
    }
}