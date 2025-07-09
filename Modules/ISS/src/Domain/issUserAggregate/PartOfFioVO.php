<?php

namespace Modules\ISS\Domain\issUserAggregate;

use \Exception;

/**
 * @var string|null $fioPart часть ФИО пользователя из основного приложения
 */

class PartOfFioVO
{
    private string|null $partOfFIO;

    public function __construct($partOfFIO)
    {
        if (empty($partOfFIO)) {
            throw new Exception("Every part of user FIO can not be empty");
        }
        $this->partOfFIO = $partOfFIO;
    }
}
