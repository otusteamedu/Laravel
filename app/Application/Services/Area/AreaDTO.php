<?php

namespace App\Application\Services\Area;

use App\Domain\BusinessModels\Area;

class AreaDTO 
{
    public ?int $id;
    public ?string $name;
    public ?string $created_at;

    public function __construct(Area $model)
    {
        $this->id = $model->getId();
        $this->name = $model->getName()->getValue();
        $this->created_at = $model->getCreatedAt();
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
