<?php

namespace App\Services\UseCases\Commands\Project\Create;

final readonly class Command
{
    public function __construct(
        public string  $name,
        public ?string $description,
        public int     $userId,
    ) {}
}
