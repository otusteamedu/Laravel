<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;


final class Roles
{
    public function __construct(public array $roles) {}
    public function has(string $role): bool { return in_array($role, $this->roles, true); }
}
