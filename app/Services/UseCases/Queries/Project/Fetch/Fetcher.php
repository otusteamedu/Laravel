<?php

namespace App\Services\UseCases\Queries\Project\Fetch;

use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\Repositories\ProjectRepositoryInterface;

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
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        return new Result(
            projectDTO: $projectDTO,
        );
    }
}
