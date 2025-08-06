<?php

namespace App\Domain\BusinessModels;

class BaseModel
{
    public ?int $id;

    public function getId(): int 
    {
        return $this->id;
    }
}
