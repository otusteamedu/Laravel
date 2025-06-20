<?php

namespace App\Repositories\Tasks;

use App\Models\Task;

interface TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function fetchAll(): array;

    /**
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function fetchPaginated(int $limit, int $offset): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task;

    /**
     * @param Task $task
     * @return bool
     */
    public function save(Task $task): bool;

    /**
     * @param Task $task
     * @return bool|null
     */
    public function delete(Task $task): ?bool;
} 