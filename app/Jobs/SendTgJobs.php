<?php

namespace App\Jobs;

use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTgJobs implements ShouldQueue
{
    use Queueable;
    public int $tries = 5;

    public function backOff(){
        return [10,20,30];
    }
    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $text,
        private string $channel
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \ProgTime\TgLogger\TgLogger::sendLog($this->text,$this->channel);
    }
}
