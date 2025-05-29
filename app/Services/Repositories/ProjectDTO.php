<?php

namespace App\Services\Repositories;

use Carbon\Carbon;


final readonly class ProjectDTO
{
    public function __construct(
        public string  $name,
        public ?string $description = null,
        public ?Carbon $created = null,
        public ?int    $id = null,
    ) {}
}
