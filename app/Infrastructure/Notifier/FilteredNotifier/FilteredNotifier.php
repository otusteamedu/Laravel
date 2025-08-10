<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier;

use App\Services\NotifierInterface;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 */
class FilteredNotifier implements NotifierInterface
{

    /** @var FilterInterface[]  */
    private array $filters;

    public function __construct(
        /**
         * Декоратор: принимает в конструктор объект с тем же интерфейсом, который сам реализует.
         *  как бы оборачивает его в себя, добавляя дополнительную логику и вызывая после этого вложенный сервис
         */
        private NotifierInterface $notifier,
        FilterInterface ...$filters
    ) {
        $this->filters = $filters;
    }

    public function send(string $text, int $recipientId): void
    {
        // Дополнительная логика фильтрации
        foreach ($this->filters as $filter) {
            if (!$filter->filter($text, $recipientId)) {
                return;
            }
        }


        // Вызов вложенного объекта
        $this->notifier->send($text, $recipientId);
    }
}
