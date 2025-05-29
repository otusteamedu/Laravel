<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

use Carbon\Carbon;

final readonly class ProjectDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public Carbon $created
    ) {}
}
