<?php

namespace App\TodoApp\Application\UseCases\Queries\TodoStatus\FetchForProject;


use Illuminate\Support\Arr;
use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Application\DTOs\TodoStatusDTO;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;


class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Список статусов задач для проекта
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $project = $this->projectRepository->find($query->projectId);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $projectDTO = new ProjectDTO(
            projectId: $project->getId()->getValue(),
            name: $project->getName()->getValue(),
            description: $project->getDescription()->getValue(),
            created: $project->getCreated()
        );

        $todoStatuses = $this->projectRepository->fetchTodoStatuses($query->projectId);

        $todoStatusesDTOs = array_map(
            fn($status) =>
            new TodoStatusDTO(
                statusId: $status->getId()->getValue(),
                projectId: $status->getProjectId()->getValue(),
                name: $status->getName(),
                sort: $status->getSort(),
                color: $status->getColor()->getValue(),
            ),
            Arr::from($todoStatuses)
        );

        return new Result(
            projectDTO: $projectDTO,
            todostatusDTOs: $todoStatusesDTOs
        );
    }
}
