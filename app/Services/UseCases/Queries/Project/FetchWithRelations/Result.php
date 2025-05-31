<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

class Result
{
    /**
     * @param ProjectDTO
     */
    public function __construct(
        public ProjectDTO $ptojectDTO,
    ) {}
}
