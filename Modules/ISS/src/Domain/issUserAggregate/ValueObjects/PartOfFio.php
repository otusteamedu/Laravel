<?php

namespace Modules\ISS\Domain\issUserAggregate\ValueObjects;

use InvalidArgumentException;

/**
 * @var string|null $fioPart часть ФИО пользователя из основного приложения
 */

final readonly class PartOfFio
{
    private string|null $partOfFIO;

    public function __construct($partOfFIO)
    {
        if (empty($partOfFIO)) {
            throw new InvalidArgumentException("Every part of user FIO can not be empty");
        }
        $this->partOfFIO = $partOfFIO;
    }
}
