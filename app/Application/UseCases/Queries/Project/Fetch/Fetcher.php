<?php

namespace App\Application\UseCases\Queries\Project\Fetch;

use App\Domain\Repositories\Exceptions\ModelNotFoundException;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

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
