<?php

declare(strict_types=1);

namespace App\Http\Controllers\Notifications;

use App\Infrastructure\Notifier\FilteredNotifier\FilteredNotifier;
use App\Services\NotifierInterface;
use Illuminate\Http\Request;

/**
 * Контроллер для отправки произвольного сообщения
 * Так как входящим данным доверять нельзя,
 * передаем в него с помощью контейнера @see FilteredNotifier с дополнительной фильтрацией
 */
class Send
{
    public function __construct(
        private NotifierInterface $notifier
    ) {
    }

    public function __invoke(Request $request, int $recipientId)
    {
        $this->notifier->send($request->get('message'), $recipientId);

        return response('OK');
    }
}