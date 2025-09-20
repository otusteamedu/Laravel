<?php

namespace App\Domain\BusinessModels;

use App\Domain\ValueObjects\Lang;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseModel
{
    protected ?int $id;
    protected Lang $lang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }
}
