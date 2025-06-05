<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Services\Repositories\ProjectUserRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectUserRepositoryInterface $repository
    ) {}

    /**
     * Возвращает массив проектов пользователя
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projectDTOs = $this->repository->fetchForUser($query->userId);

        return new Result(
            ptojectDTOs: $projectDTOs
        );
    }
}
