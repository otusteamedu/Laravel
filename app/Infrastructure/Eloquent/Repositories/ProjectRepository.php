<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Models\Project;
use App\Services\Repositories\DTOs\ProjectDTO;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Получить проект по id
     * @param int $id
     * @return ProjectDTO|null
     */
    public function find(int $id): ?ProjectDTO
    {
        $project = Project::query()
            ->where('id', $id)
            ->first();

        if ($project === null) {
            return null;
        }

        return new ProjectDTO(
            id: $project->id,
            name: $project->name,
            description: $project->description,
            created: $project->created_at,
        );
    }

    /**
     * Добавить данные проекта
     * @param ProjectDTO $project
     * @return int
     */
    public function add(ProjectDTO $project): int
    {
        $dbProject = Project::create([
            'name'        => $project->name,
            'description' => $project->description,
        ]);

        return $dbProject->refresh()->id;
    }

    /**
     * Обновить данные проекта
     * @param ProjectDTO $project
     * @return bool
     */
    public function save(ProjectDTO $project): bool
    {
        return Project::query()
            ->where('id', $project->id)
            ->update([
                'name'        => $project->name,
                'description' => $project->description,
            ]);
    }

    /**
     * Удалить проект
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return Project::where('id', $id,)
            ->delete() ?? false;
    }
}
