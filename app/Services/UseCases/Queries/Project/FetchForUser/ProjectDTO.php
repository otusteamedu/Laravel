<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use Illuminate\Support\Carbon;



final readonly class ProjectDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public Carbon $created
    ) {}
}
