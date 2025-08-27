<?php

namespace ISS\App\Infrastructure\Events\CheckExamDates;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ISS\App\Infrastructure\Events\CheckExamDates\CheckExamDatesDTO;

class CheckExamDates
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CheckExamDatesDTO $dto;

    /**
     * Create a new event instance.
     */
    public function __construct(CheckExamDatesDTO $dto)
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
