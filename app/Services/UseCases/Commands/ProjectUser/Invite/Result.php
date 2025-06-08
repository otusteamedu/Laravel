<?php

namespace App\Services\UseCases\Commands\ProjectUser\Invite;

final readonly class Result
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
