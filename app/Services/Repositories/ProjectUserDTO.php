<?php

namespace App\Services\Repositories;

use Carbon\Carbon;


final readonly class ProjectUserDTO
{
    public function __construct(
        public int     $project_id,
        public int     $user_id,
        public array   $roles,
        public ?Carbon $invited = null,
        public ?Carbon $joined = null,
        public ?Carbon $left = null,
        public ?int    $id = null,
    ) {}
}
