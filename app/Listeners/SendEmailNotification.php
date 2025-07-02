<?php

namespace App\Listeners;

use App\Events\NewsPublished;
use App\Mail\NewsPublishedMail;
use App\Services\UseCases\Queries\FetchUsersSubscribedNews\Fetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private Fetcher $fetcher)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsPublished $event): void
    {
        $subscribers = $this->fetcher->fetch()->results;

        foreach ($subscribers as $user) {
            Mail::to($user->email)->queue(new NewsPublishedMail($event->id, $event->title, $event->content));
        }
    }
}
