<?php

namespace App\Modules\ISS\src\Events\ExamChecked;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Modules\ISS\src\Events\ExamChecked\ExamCheckedDTO;

class ExamChecked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ExamCheckedDTO $dto;

    /**
     * Create a new event instance.
     */
    public function __construct(ExamCheckedDTO $dto)
    {
        $this->dto = $dto;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
