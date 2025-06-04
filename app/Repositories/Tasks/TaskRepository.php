<?php

namespace App\Repositories\Tasks;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function fetchAll(): array {
        return Task::with(['executor', 'category', 'priority'])->get()->all();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator {
        return Task::with(['executor', 'category', 'priority'])->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task {
        return Task::with(['executor', 'category', 'priority'])->find($id);
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