<?php

namespace App\Services\NotificationService;

use Illuminate\Log\LogManager;

class MultiNotificationService implements MultiNotificationServiceInterface
{

    private $notificationServices;
    public function __construct(NotificationServiceInterface ...$notificationServices)
    {
        $this->notificationServices = $notificationServices;
    }

    public function notify(\App\Models\User $user, \App\VO\NotificationText $text)
    {
        foreach ($this->notificationServices as $notificationService) {
            $notificationService->notify($user, $text);
        }
    }
}
