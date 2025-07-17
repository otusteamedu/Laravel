<?php

namespace Modules\ISS\Domain\issUserAggregate\ValueObjects;

use InvalidArgumentException;

/**
 * @var string $name название роли пользователя ИОС
 */

final readonly class RoleName
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("User name must be not empty");
        }

        if (!in_array(
                $name,
                [
                'admin',//config('issModule.ROLE_ADMIN'),
                'manager', //config('issModule.ROLE_MANAGER'),
                'employee', //config('issModule.ROLE_EMPLOYEE')
                ]
            )
        ) {
            throw new InvalidArgumentException("User role name must be in set of allowed roles");
        }

        $this->name = $name;
    }
}
