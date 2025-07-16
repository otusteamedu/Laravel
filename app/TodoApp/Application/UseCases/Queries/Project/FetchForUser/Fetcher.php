<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\FetchForUser;

use Illuminate\Support\Arr;
use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;


class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $repository
    ) {}

    /**
     * Возвращает массив проектов пользователя
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projects = $this->repository->fetchUserProjects($query->userId);

        $projectDTOs = array_map(
            fn($project) =>
            new ProjectDTO(
                projectId: $project->getId()->getValue(),
                name: $project->getName()->getValue(),
                description: $project->getDescription()->getValue(),
                created: $project->getCreated()
            ),
            Arr::from($projects)
        );

        return new Result(
            projectDTOs: $projectDTOs
        );
    }
}
