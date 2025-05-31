<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use App\Services\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Arr;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class Fetcher
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Список статусов задач для проекта
     * @param \App\Services\UseCases\Queries\TodoStatus\FetchForProject\Query $query
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
            created: $project->created,
        );

        $todoStatuses = $this->repository->fetchForProject($query->projectId);

        $todoStatusesDTOs = array_map(
            fn($status) =>
            new TodoStatusDTO(
                id: $status->id,
                project_id: $status->project_id,
                name: $status->name,
                sort: $status->sort,
                color: $status->color,
            ),
            Arr::from($todoStatuses)
        );

        return new Result(
            projectDTO: $projectDTO,
            todostatusDTOs: $todoStatusesDTOs
        );
    }
}
