<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var string|null $organization название организации пользователя из основного приложения
 */

class OrganizationVO
{
    private string|null $organization;

    public function __construct(string|null $organization)
    {
        if (empty($organization)){
            throw new Exception("User organization must be not empty");
        }
        $this->organization = $organization;

    }
}
