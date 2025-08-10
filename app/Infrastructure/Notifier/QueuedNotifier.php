<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier;

use App\Jobs\SendNotificationJob;
use App\Services\NotifierInterface;
use Illuminate\Bus\Dispatcher;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 *
 * Вместо отправки уведомления сразу, добавляет задачу в очередь
 */
class QueuedNotifier implements NotifierInterface
{

    public function __construct(
        private Dispatcher $dispatcher,
    ) {
    }

    public function send(string $text, int $recipientId): void
    {
        $this->dispatcher->dispatch(
            new SendNotificationJob(
                $text,
                $recipientId
            )
        );
    }
}
