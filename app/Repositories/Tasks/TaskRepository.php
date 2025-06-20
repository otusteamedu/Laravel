<?php

namespace App\Repositories\Tasks;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function fetchAll(): array {
        return Task::with(['executor', 'category', 'priority', 'creator'])->get()->all();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        return Task::with(['executor', 'category', 'priority', 'creator'])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->all();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return Task::count();
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task {
        return Task::with(['executor', 'category', 'priority', 'creator'])->find($id);
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function save(Task $task): bool {
        return $task->save();
    }

    /**
     * @param Task $task
     * @return bool|null
     */
    public function delete(Task $task): ?bool {
        return $task->delete();
    }
}
