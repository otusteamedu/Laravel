<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\TodoStatus;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\DTOs\InsertTodoStatusesDTO;
use App\Services\Repositories\TodoStatusRepositoryInterface;


class TodoStatusRepository implements TodoStatusRepositoryInterface
{
    /**
     * Получить статус по id
     * @param int $id
     * @param int $projectId
     * @return \App\Models\TodoStatus|null
     */
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

    /**
     * Добавить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return int
     */
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

    /**
     * Обновить данные статуса для задач проекта
     * @param TodoStatusDTO $status
     * @return bool
     */
    public function save(TodoStatusDTO $status): bool
    {
        $updated = TodoStatus::query()
            ->where('id', $status->id)
            ->where('project_id', $status->project_id)
            ->update([
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ]);

        return $updated ? true : false;
    }

    /**
     * Удалить статус задач для проекта
     * @param int $id
     * @param int $projectId
     * @return bool
     */
    public function destroy(int $id, int $projectId): bool
    {
        return TodoStatus::query()
            ->where('id', $id)
            ->where('project_id', $projectId)
            ->delete() ?? false;
    }

    /**
     * Массовое добавление статусов в проект
     */
    public function insert(InsertTodoStatusesDTO $statuses): bool
    {
        $data = json_decode(json_encode($statuses->todoStatusDTOs), true);
        return TodoStatus::insert($data);
    }

    /**
     * Список доступных для задач проекта статусов
     * @param int $projectId
     * @return TodoStatusDTO[]
     */
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
