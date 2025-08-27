<?php

namespace ISS\App\Infrastructure\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use ISS\App\Infrastructure\Events\ExamChecked\ExamChecked;
use ISS\App\Infrastructure\Mails\IssExamStatusNotify;

class SendStudentNotifyJob implements ShouldQueue
{
    use Queueable;

    public ExamChecked $event;

    /**
     * Create a new job instance.
     */
    public function __construct(ExamChecked $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->event->dto->studentEmail)->send(
            new IssExamStatusNotify(
                $this->event->dto->scheduledExamDate,
                $this->event->dto->pointName,
                $this->event->dto->routeName,
                $this->event->dto->examCheckResult
            )
        );
    }

    public function failed()
    {
        Log::error('Notification for student failed (info' . json_encode($this->event->dto) . ')');
    }

    public function backoff(): array
    {
        return [5, 10, 60, 60*5, 60*60];
    }
}
