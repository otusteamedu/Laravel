<?php

namespace App\Services\NotificationService;

use Illuminate\Log\LogManager;

class LogNotificationService implements NotificationServiceInterface
{

    public function __construct(private readonly LogManager $logManager)
    {

    }

    public function notify(\App\Models\User $user, \App\VO\NotificationText $text)
    {
        $this->logManager->info('Notification', ['user' => $user, 'text' => $text->getText()]);
    }
}
