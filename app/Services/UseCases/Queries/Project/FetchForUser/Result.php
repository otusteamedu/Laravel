<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

class Result
{
    /**
     * @param array ProjectDTO[]
     */
    public function __construct(
        public array $ptojectDTOs,
    ) {}
}
