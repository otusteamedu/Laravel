<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Services\Repositories\ProjectRepository;

class Fetcher
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {}

    /**
     * Возвращает массив проектов пользователя
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projectDTOs = $this->projectRepository->fetchForUser($query->userId);

        return new Result(
            ptojectDTOs: $projectDTOs
        );
    }
}
