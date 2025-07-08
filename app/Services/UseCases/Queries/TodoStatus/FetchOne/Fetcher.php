<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchOne;

use App\Services\Repositories\DTOs\TodoStatusDTO;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Список статусов задач для проекта
     * @param Query $query
     * @return TodoStatusDTO
     */
    public function fetch(Query $query): TodoStatusDTO
    {
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $statusDTO = $this->projectRepository->findTodoStatus($query->projectId, $query->statusId);

        if ($statusDTO === null) {
            throw new ModelNotFoundException('Стататус не найден в проекте');
        }

        return $statusDTO;
    }
}
