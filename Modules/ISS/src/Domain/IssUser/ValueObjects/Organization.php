<?php

namespace ISS\App\Domain\IssUser\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $organization название организации пользователя из основного приложения
 */

final readonly class Organization
{
    public string|null $organization;

    public function __construct(string|null $organization)
    {
        if (empty($organization)){
            throw new InvalidArgumentException("User organization must be not empty");
        }
        $this->organization = $organization;

    }
}
