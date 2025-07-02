<?php

namespace App\Services\UseCases\Commands\Mail\Todo\UserAdd;

final readonly class Command
{
    public function __construct(
        public int $userId,
        public int $projectId,
        public int $todoId,
        public string $role
    ) {}
}
