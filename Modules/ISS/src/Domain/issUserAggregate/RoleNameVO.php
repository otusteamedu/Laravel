<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var string $name название роли пользователя ИОС
 */

class RoleNameVO
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new Exception("User name must be not empty");
        }

        if (!in_array(
                $name,
                [
                config('issModule.ROLE_ADMIN'),
                config('issModule.ROLE_MANAGER'),
                config('issModule.ROLE_EMPLOYEE')
                ]
            )
        ) {
            throw new Exception("User role name must be in set of allowed roles");
        }

        $this->name = $name;
    }
}
