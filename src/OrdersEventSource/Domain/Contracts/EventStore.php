<?php
namespace Src\OrdersEventSource\Domain\Contracts;

interface EventStore
{
    public function load(
        string $aggregateId
    ): array;

    public function append(
        string $aggregateId,
        array $events
    ): void;
}