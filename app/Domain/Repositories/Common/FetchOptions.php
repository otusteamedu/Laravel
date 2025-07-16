<?php

namespace App\Domain\Repositories\Common;

final readonly class FetchOptions
{
    public function __construct(
        public ?array $ids = null,
        public ?int $perPage = null,
        public ?int $page = null,
    ) {}
}
