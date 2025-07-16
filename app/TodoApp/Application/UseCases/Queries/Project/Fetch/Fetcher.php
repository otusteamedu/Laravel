<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\Fetch;

use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
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

        return new Result(
            projectDTO: $projectDTO,
        );
    }
}
