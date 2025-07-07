<?php

namespace App\Services\UseCases\Queries\Todo\FetchForProject;

use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoRepositoryInterface $todoRepository,
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

        $todoDTOs = $this->todoRepository->fetchForProject($query->projectId, $query->userId);

        $projectDTO = new ProjectDTO(
            projectId: $project->getId()->getValue(),
            name: $project->getName()->getValue(),
            description: $project->getDescription()->getValue(),
            created: $project->getCreated()
        );

        return new Result(
            projectDTO: $projectDTO,
            todoDTOs: $todoDTOs
        );
    }
}
