<?php

namespace App\Events\Todo;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserAssignTodoRoleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public int $projectId,
        public int $todoId,
        public string $role
    ) {
        //
    }
}
