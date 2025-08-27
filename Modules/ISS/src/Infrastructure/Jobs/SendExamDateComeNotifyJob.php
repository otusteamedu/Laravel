<?php

namespace ISS\App\Infrastructure\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use ISS\App\Infrastructure\Listeners\SendNotifyExamDateDTO;
use ISS\App\Infrastructure\Mails\IssExamDateCome;

/** Отправляет письмо сотруднику, о том что подходит срок сдачи экзамена для очередной точки обучающего маршрута */

class SendExamDateComeNotifyJob implements ShouldQueue
{
    use Queueable;

    //public CheckExamDates $event;
    public SendNotifyExamDateDTO $dto;

    /**
     * Create a new job instance.
     */
    public function __construct(
        //CheckExamDates $event
        SendNotifyExamDateDTO $dto
    )
    {
        $this->dto = $dto;
    }

    //___________________________
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->dto->issUserEmail)->send(
            new IssExamDateCome(
                $this->dto->issUserName,
                $this->dto->issUserSecondName,
                $this->dto->issUserLastName,
                $this->dto->routeName,
                $this->dto->pointName,
                $this->dto->examDate,
            )
        );
    }

    public function failed()
    {
        Log::error('Notification for student exam date come (info' . json_encode($this->dto) . ')');
    }

    public function backoff(): array
    {
        return [5, 10, 60, 60*5, 60*60];
    }
}
