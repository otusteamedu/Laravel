<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\TodoStatus;
use Illuminate\Support\Arr;
use App\Services\Repositories\TodoStatusDTO;
use App\Services\Repositories\InsertTodoStatusesDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class TodoStatusRepository implements TodoStatusRepositoryInterface
{
    public function find(int $id, int $projectId): ?TodoStatusDTO
    {
        $status = TodoStatus::query()
            ->where('id', $id)
            ->where('project_id', $projectId)
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
            ->where('project_id', $status->project_id)
            ->update([
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ]);
    }

    public function destroy(int $id, int $projectId): bool
    {
        return TodoStatus::query()
            ->where('id', $id)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }

    public function insert(InsertTodoStatusesDTO $statuses): bool
    {
        $data = Arr::from($statuses->todoStatusDTOs);

        return TodoStatus::insert($data);
    }

    public function fetchForProject(int $projectId): array
    {
        $dbStatuses = TodoStatus::query()
            ->where('project_id', $projectId)
            ->orderBy('sort')
            ->get();

        return array_map(
            fn($status) =>
            new TodoStatusDTO(
                id: $status['id'],
                project_id: $status['project_id'],
                name: $status['name'],
                sort: $status['sort'],
                color: $status['color'],
            ),
            $dbStatuses->toArray()
        );
    }
}
