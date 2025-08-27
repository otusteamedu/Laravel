<?php

namespace ISS\App\Infrastructure\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ISS\App\Infrastructure\Jobs\SendTeacherMailJob;
use ISS\App\Infrastructure\Events\ExamChecked\ExamChecked;

class SendTeacherMailListener// implements ShouldQueue
{
    //use InteractsWithQueue;

    //public $connection = 'database';
    //public $queue = 'default';
    //public $delay = 10;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    //public function shouldQueue(){ return true; }

    /**
     * Handle the event.
     */
    public function handle(ExamChecked $event): void
    {
        if ($event->dto->needMailToTeacher) {
            SendTeacherMailJob::dispatch($event)->onQueue('iss')->delay(2);
        }

    }
}
