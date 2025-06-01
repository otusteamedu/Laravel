<?php

namespace App\Services\Repositories;

use App\Models\TodoStatus;
use Illuminate\Support\Arr;
use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\DTOs\InsertTodoStatusesDTO;


class TodoStatusRepository
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
        return TodoStatus::query()
            ->where('id', $status->id)
            ->where('project_id', $status->project_id)
            ->update([
                'name'       => $status->name,
                'sort'       => $status->sort,
                'color'      => $status->color,
            ]);
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
        $data = Arr::from($statuses->todoStatusDTOs);

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
