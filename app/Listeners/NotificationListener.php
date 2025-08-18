<?php

     namespace App\Listeners;

     
     use Illuminate\Contracts\Queue\ShouldQueue;
     use Illuminate\Queue\InteractsWithQueue;
     use App\Events\NotificationEvent;

     class NotificationListener implements ShouldQueue
     {
         use InteractsWithQueue;

         /**
          * Handle the event.
          *
          * @param  \App\Events\NotificationEvent  $event
          * @return void
          */
         public function handle(NotificationEvent $event)
         {
            \ProgTime\TgLogger\TgLogger::sendLog('Новость создана','debug');
         }
     }