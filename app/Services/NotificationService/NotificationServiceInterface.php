<?php

namespace App\Services\NotificationService;

use App\Models\User;
use App\VO\NotificationText;

interface NotificationServiceInterface
{
    public function notify(User $user, NotificationText $text);
}
