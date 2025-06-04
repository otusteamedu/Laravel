<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\TodoStatusRepositoryInterface;

class Fetcher
{
    public function __construct(
        private TodoStatusRepositoryInterface $repository,
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Список статусов задач для проекта
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $todoStatusesDTOs = $this->repository->fetchForProject($query->projectId);

        return new Result(
            projectDTO: $projectDTO,
            todostatusDTOs: $todoStatusesDTOs
        );
    }
}
