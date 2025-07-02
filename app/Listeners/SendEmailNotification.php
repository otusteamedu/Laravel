<?php

namespace App\Listeners;

use App\Events\NewsPublished;
use App\Services\Queries\FetchUsersSubscribedNews\Fetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsPublishedMail;
use Throwable;
use RuntimeException;

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
       // try {
            $subscribers = $this->fetcher->fetch()->results;

            foreach ($subscribers as $user) {
                Mail::to($user->email)->queue(new NewsPublishedMail($event->id, $event->title, $event->content));
            }
//        } catch (Throwable $exception) {
//            throw new RuntimeException('Failed to send email notifications', 0, $exception);
//        }
    }
}
