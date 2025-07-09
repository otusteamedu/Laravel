<?php

namespace App\Modules\ISS\src\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\ISS\src\Events\ExamChecked\ExamChecked;
use App\Modules\ISS\src\Jobs\SendStudentNotifyJob;

class SendStudentNotifyListener// implements ShouldQueue
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
           SendStudentNotifyJob::dispatch($event)->onQueue('iss')->delay(5);
    }
}
