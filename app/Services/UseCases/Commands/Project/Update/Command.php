<?php

namespace App\Services\UseCases\Commands\Project\Update;

final readonly class Command
{
    public function __construct(
        public int     $id,
        public string  $name,
        public ?string $description,
    ) {}
}
