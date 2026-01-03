<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService\NotificationServiceInterface;
use App\VO\NotificationText;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly NotificationText $text
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationServiceInterface $notificationService): void
    {
        $notificationService->notify(
            $this->user,
            $this->text
        );
    }
}
