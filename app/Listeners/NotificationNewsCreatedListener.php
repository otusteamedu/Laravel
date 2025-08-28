<?php

    namespace App\Listeners;
    
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Queue\InteractsWithQueue;
    use App\Events\NotificationNewsCreatedEvent;

    class NotificationNewsCreatedListener implements ShouldQueue
    {
        use InteractsWithQueue;

        /**
         * Handle the event.
        *
        * @param  \App\Events\NotificationNewsCreatedEvent  $event
        * @return void
        */
        public function handle(NotificationNewsCreatedEvent $event)
        {            
            \ProgTime\TgLogger\TgLogger::sendLog('Новость создана с id: '.$event->newsId,'debug');
        }
    }