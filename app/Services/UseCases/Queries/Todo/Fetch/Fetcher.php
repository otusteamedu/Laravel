<?php

namespace App\Services\UseCases\Queries\Todo\Fetch;

use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoRepositoryInterface $todoRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $todoDTO = $this->todoRepository->find($query->todoId, $query->projectId);

        if ($todoDTO === null) {
            throw new ModelNotFoundException('Задача не найдена');
        }

        return new Result(
            projectDTO: $projectDTO,
            todoDTO: $todoDTO,
        );
    }
}
