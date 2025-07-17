<?php

namespace Modules\ISS\Domain\SharedValueObjects;

use InvalidArgumentException;

/**
 * @var int $id код идентификатор
 */

final readonly class Id
{
    private int $id;

    public function __construct(int $id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("User ID must be numeric more than 0");
        }
        if (is_null($id)) {
            throw new InvalidArgumentException("User ID can't be null");
        }
        $this->id = $id;
    }
}
