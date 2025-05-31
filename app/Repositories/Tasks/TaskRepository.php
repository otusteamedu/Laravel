<?php

namespace App\Repositories\Tasks;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @param Task $task
     */
    public function __construct(private Task $task)
    {
    }

    /**
     * @return array
     */
    public function fetchAll(): array {
        return $this->task::with(['executor', 'category', 'priority'])->get()->all();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator {
        return $this->task->with(['executor', 'category', 'priority'])->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task {
        return $this->task->with(['executor', 'category', 'priority'])->find($id);
    }

    /**
     * @return Task
     */
    public function create(): Task {
        return $this->task;
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