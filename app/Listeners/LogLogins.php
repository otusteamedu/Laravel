<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Psr\Log\LoggerInterface;

class LogLogins
{
    public function __construct(
        private LoggerInterface $logger
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->logger->emergency('USER LOGGED IN: ' . $event->user->getAuthIdentifier());
    }
}
