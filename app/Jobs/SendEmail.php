<?php

namespace App\Jobs;

use App\Services\SendEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;

class SendEmail implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Queueable;

    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $to,
        public string $subject,
        public string $body
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SendEmailService $service): void
    {
        $service->sendEmail($this->to, $this->subject, $this->body);
    }
}
