<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Services\Repositories\DTOs\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO[] $ptojectDTOs
     */
    public function __construct(
        public array $ptojectDTOs,
    ) {}
}
