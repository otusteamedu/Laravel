<?php

namespace App\Services\UseCases\Commands\Project\Update;

final readonly class Command
{
    /**
     * @param int $id
     * @param string $name
     * @param mixed $description
     */
    public function __construct(
        public int     $id,
        public string  $name,
        public ?string $description,
    ) {}
}
