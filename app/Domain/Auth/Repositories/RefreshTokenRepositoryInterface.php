<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function create(array $data): RefreshToken;

    public function findByToken(string $hashedToken): ?RefreshToken;

    public function deleteByToken(string $hashedToken): void;

    public function save(RefreshToken $token): bool;
}

