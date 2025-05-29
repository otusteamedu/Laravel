<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\TodoStatus;
use App\Services\Repositories\TodoStatusDTO;
use App\Services\Repositories\InsertTodoStatusesDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class TodoStatusRepository implements TodoStatusRepositoryInterface
{
    public function find(int $id): ?TodoStatusDTO
    {
        $status = TodoStatus::query()
            ->where('id', $id)
            ->first();

        if ($status === null) {
            return null;
        }

        return new TodoStatusDTO(
            id: $status->id,
            project_id: $status->project_id,
            name: $status->name,
            sort: $status->sort,
            color: $status->color,
        );
    }

    public function add(TodoStatusDTO $status): int
    {
        $dbStatus = TodoStatus::create([
            'project_id' => $status->project_id,
            'name'       => $status->name,
            'sort'       => $status->sort,
            'color'      => $status->color,
        ]);

        return $dbStatus->refresh()->id;
    }

    public function save(TodoStatusDTO $status): bool
    {
        return TodoStatus::query()
            ->where('id', $status->id)
            ->update([
                'project_id' => $status->project_id,
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ]);
    }

    public function destroy(int $id): bool
    {
        return TodoStatus::query()
            ->where('id', $id)
            ->delete() ?? false;
    }

    public function insert(InsertTodoStatusesDTO $statuses): bool
    {
        $data = json_decode(json_encode($statuses->todoStatusDTOs), true);

        return TodoStatus::insert($data);
    }
}
