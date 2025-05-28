<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\TodoStatus;
use App\Services\Repositories\InsertTodoStatusesDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class TodoStatusRepository implements TodoStatusRepositoryInterface
{
    public function find(int $id): ?TodoStatus
    {
        return TodoStatus::query()
            ->where('id', $id)
            ->first();
    }

    public function findWithProject(int $id, int $projectId): ?TodoStatus
    {
        return TodoStatus::query()
            ->where('id', $id)
            ->where('project_id', $projectId)
            ->first();
    }

    public function add(TodoStatus $status): int
    {
        $status->save();

        return $status->refresh()->id;
    }

    public function save(TodoStatus $status): void
    {
        $status->save();
    }

    public function destroy(TodoStatus $status): void
    {
        $status->delete();
    }

    public function insert(InsertTodoStatusesDTO $statuses): bool
    {
        $data = json_decode(json_encode($statuses->todoStatusDTOs), true);

        return TodoStatus::insert($data);
    }
}
