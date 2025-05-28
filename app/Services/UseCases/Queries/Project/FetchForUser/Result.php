<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

class Result
{
    /**
     * @param array PrjoectDTO[]
     */
    public function __construct(
        public array $ptojectDTOs,
    ) {}
}
