<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var int $id код идентификатор
 */

class IdVO
{
    private $id;

    public function __construct(int $id)
    {
        if ($id == 0 || $id < 0) {
            throw new Exception("User ID must be numeric more than 0");
        }
        if (is_null($id)) {
            throw new Exception("User ID can't be null");
        }
        $this->id = $id;
    }
}
