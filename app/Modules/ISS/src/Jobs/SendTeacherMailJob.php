<?php

namespace App\Modules\ISS\src\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Modules\ISS\src\Events\ExamChecked\ExamChecked;
use App\Modules\ISS\src\Mails\IssExamTeacherMail;


class SendTeacherMailJob implements ShouldQueue
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
         Mail::to($this->event->dto->teacherEmail)
                ->send(
                    new IssExamTeacherMail(
                        $this->event->dto->teacherURL,
                        $this->event->dto->examCheckCode,
                        $this->event->dto->checkedQuestionsWithText
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
