<?php

namespace App\Application\UseCases\Queries\Project\FetchForUser;

use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

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
        $projectDTOs = $this->repository->fetchUserProjects($query->userId);

        return new Result(
            projectDTOs: $projectDTOs
        );
    }
}
