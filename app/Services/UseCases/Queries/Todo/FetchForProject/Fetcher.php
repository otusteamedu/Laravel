<?php

namespace App\Services\UseCases\Queries\Todo\FetchForProject;

use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
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
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $todoDTOs = $this->todoRepository->fetchForProject($query->projectId, $query->userId);

        return new Result(
            projectDTO: $projectDTO,
            todoDTOs: $todoDTOs
        );
    }
}
