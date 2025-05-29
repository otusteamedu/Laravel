<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Services\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Возвращает массив проектов пользователя
     * @param \App\Services\UseCases\Queries\Project\FetchForUser\Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projectDTOs = $this->projectRepository->fetchForUser($query->userId);

        return new Result($projectDTOs);
    }
}
