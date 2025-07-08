<?php

namespace App\Http\API\V1\Responses;

final readonly class ErrorResponse
{
    public function __construct(
        public string $message,
        public array $errors,
        public int $code,
    ) {}
}
