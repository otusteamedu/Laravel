<?php

namespace App\Http\API\V1\Responses;

final readonly class SuccessResponse
{
    public function __construct(
        public mixed $payload,
        public bool $success,
        public int $code,
    ) {}
}
