<?php

namespace App\Domain\BusinessModels;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseModel
{
    protected ?int $id;

    public function getId(): ?int 
    {
        return $this->id;
    }
}
