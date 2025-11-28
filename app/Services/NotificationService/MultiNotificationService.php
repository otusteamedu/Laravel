<?php

namespace App\Services\NotificationService;


class MultiNotificationService implements NotificationServiceInterface
{
    private $notificationServices;
    public function __construct(...$notificationServices)
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
