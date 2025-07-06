<?php

namespace App\Application\UseCases\User\DTO;

final readonly class ResultDTO
{
    /**
     * @param UserDTO[] $items
     */
    public function __construct(
        public array $items
    ) {
    }
}
