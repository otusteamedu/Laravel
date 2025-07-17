<?php

namespace Modules\ISS\Domain\issUserAggregate\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $organization название организации пользователя из основного приложения
 */

final readonly class Organization
{
    private string|null $organization;

    public function __construct(string|null $organization)
    {
        if (empty($organization)){
            throw new InvalidArgumentException("User organization must be not empty");
        }
        $this->organization = $organization;

    }
}
