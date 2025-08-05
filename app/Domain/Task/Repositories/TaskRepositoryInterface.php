<?php

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Aggregates\Task;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\UserId;

interface TaskRepositoryInterface
{
    public function save(Task $task): bool;

    public function findById(TaskId $id): ?Task;

    public function findAll(): array;

    public function findByExecutor(UserId $executorId): array;

    public function findByCreator(UserId $creatorId): array;

    public function delete(TaskId $id): bool;

    public function count(): int;

    public function findPaginated(int $limit, int $offset): array;
}