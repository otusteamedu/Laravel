<?php

namespace App\Application\Services\Area;

use App\Domain\BusinessModels\Area;

class AreaDTO 
{
    public ?int $id;
    public ?string $name;
    public ?string $created_at;

    public function __construct(Area $area)
    {
        $this->id = $area->getId();
        $this->name = $area->getName()->getValue();
        $this->created_at = $area->getCreatedAt();
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at
        ];
    }
}
