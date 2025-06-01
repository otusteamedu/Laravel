<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

use App\Services\Repositories\DTOs\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO
     * @param UserWithRelationsDTO[]
     */
    public function __construct(
        public ProjectDTO $ptojectDTO,
        public array $userDTOs,
    ) {}
}
