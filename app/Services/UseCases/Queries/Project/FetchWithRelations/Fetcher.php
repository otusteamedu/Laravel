<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

use App\Models\Project;
use App\Models\TodoStatus;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoStatusRepositoryInterface $todoStatusRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
     * @param \App\Services\UseCases\Queries\Project\FetchWithRelations\Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $project = $this->projectRepository->find($query->projectId);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $projectDTO = new ProjectDTO(
            id: $project->id,
            name: $project->name,
            description: $project->description,
            created: $project->created_at,
        );

        $todoStatuses = $this->projectRepository->fetchTodoStatuses($query->projectId);

        $todoStatusDTOs = array_map(
            function (TodoStatus $status) {
                return new TodoStatusDTO(
                    id: $status->id,
                    name: $status->name,
                    color: $status->color,
                    sort: $status->sort,
                );
            },
            $todoStatuses
        );


        return new Result(
            ptojectDTO: $projectDTO,
            todoStatusDTOs: $todoStatusDTOs,
        );
    }
}
