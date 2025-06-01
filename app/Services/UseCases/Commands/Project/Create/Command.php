<?php

namespace App\Services\UseCases\Commands\Project\Create;

final readonly class Command
{
    /**
     * @param string $name
     * @param string|null $description
     * @param int $userId
     */
    public function __construct(
        public string  $name,
        public ?string $description,
        public int     $userId,
    ) {}
}
