<?php

namespace App\Repositories\Tasks;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function fetchAll(): array;

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task;

    /**
     * @return Task
     */
    public function create(): Task;

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