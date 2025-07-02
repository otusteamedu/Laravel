<?php

namespace App\Listeners;

use App\Events\NewsPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogNewsPublished implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsPublished $event)
    {
        //Log::info("Новость опубликована: ID {$event->id}, Заголовок: {$event->title}");
    }
}
