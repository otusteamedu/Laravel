<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Psr\Log\LoggerInterface;

class LoginLoggingEventListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->logger->emergency('User logged in', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip_address' => request()->ip(),
        ]);
    }
}
