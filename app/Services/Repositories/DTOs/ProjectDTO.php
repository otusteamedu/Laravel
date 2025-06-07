<?php

namespace App\Services\Repositories\DTOs;

use Carbon\Carbon;


final readonly class ProjectDTO
{
    /**
     * @param string $name
     * @param string|null $description
     * @param Carbon|null $created
     * @param int|null $projectId
     */
    public function __construct(
        public string  $name,
        public ?string $description = null,
        public ?Carbon $created = null,
        public ?int    $projectId = null,
    ) {}
}
