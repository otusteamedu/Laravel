<?php

namespace ISS\App\Domain\SharedValueObjects;

use InvalidArgumentException;

/**
 * @var int $id код идентификатор
 */

final readonly class Id
{
    public int $id;

    public function __construct(int $id)
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID must be numeric more than 0");
        }
        if (is_null($id)) {
            throw new InvalidArgumentException("ID can't be null");
        }
        $this->id = $id;
    }
}
