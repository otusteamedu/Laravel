<?php

namespace Src\OrdersEventSource\Infrastructure\Stores;

use Illuminate\Support\Facades\DB;
use Src\OrdersEventSource\Domain\Contracts\EventStore;
use Src\OrdersEventSource\Domain\Events\OrderCreated;
use Src\OrdersEventSource\Domain\Events\OrderItemAdded;
use Src\OrdersEventSource\Domain\Events\OrderPaid;
use Src\OrdersEventSource\Domain\ValueObjects\OrderId;
use Src\OrdersEventSource\Domain\ValueObjects\OrderPrice;

class EloquentEventStore implements EventStore
{
    public function append(
        string $aggregateId,
        array $events
    ): void {

        foreach ($events as $event) {
            DB::table('event_store')->insert([
                'aggregate_id' => $aggregateId,
                'event_type' => get_class($event),
                'payload' => json_encode(
                    $this->serializeEvent($event)
                ),
            ]);
        }
    }

    public function load(
        string $aggregateId
    ): array {

        return DB::table('event_store')
            ->where('aggregate_id', $aggregateId)
            ->orderBy('id')
            ->get()
            ->map(
                fn ($row) => $this->deserializeEvent($row)
            )
            ->all();
    }

    private function serializeEvent(
        object $event
    ): array {

        return match (true) {
            $event instanceof OrderCreated => [
                'orderId' => $event->orderId->value(),
            ],

            $event instanceof OrderPaid => [
                'orderId' => $event->orderId->value(),
            ],

            $event instanceof OrderItemAdded => [
                'productId' => $event->productId,
                'qty' => $event->qty,
                'price' => new OrderPrice($event->price->value(), '$')
            ],

            default => throw new \RuntimeException(
                'Unknown event: ' . get_class($event)
            ),
        };
    }

    private function deserializeEvent(
        object $row
    ): object {
        $payload = json_decode(
            $row->payload,
            true
        );

        return match ($row->event_type) {
            OrderCreated::class =>
                new OrderCreated(
                    new OrderId($payload['orderId'])
                ),

            OrderPaid::class =>
                new OrderPaid(
                    new OrderId($payload['orderId'])
                ),

            OrderItemAdded::class =>
                new OrderItemAdded(
                    $payload['productId'],
                    $payload['qty'],
                    $payload['price']
                ),

            default => throw new \RuntimeException(
                'Unknown event type: ' . $row->event_type
            ),
        };
    }
}