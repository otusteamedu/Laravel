<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

final class Permissions
{
    public function __construct(public array $permissions) {}
    public function has(string $permission): bool { return in_array($permission, $this->permissions, true); }
}
