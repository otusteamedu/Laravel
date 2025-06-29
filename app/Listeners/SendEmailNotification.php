<?php

namespace App\Listeners;

use App\Events\NewsPublished;
use App\Services\User\Fetchers\GetSubscribedNewsHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsPublishedMail;

class SendEmailNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private GetSubscribedNewsHandler $getSubscribedNewsHandler)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsPublished $event): void
    {
        $subscribers = $this->getSubscribedNewsHandler->__invoke()->results;

        foreach ($subscribers as $user) {
            Mail::to($user->email)->queue(new NewsPublishedMail($event->id, $event->title, $event->content));
        }
    }
}
