<?php

namespace App\Events\Todo;

use App\Models\TodoRoleEnum;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserAssignTodoRoleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * @param int $userId
     * @param int $projectId
     * @param int $todoId
     * @param \App\Models\TodoRoleEnum $role
     */
    public function __construct(
        public int $userId,
        public int $projectId,
        public int $todoId,
        public TodoRoleEnum $role
    ) {
        //
    }
}
