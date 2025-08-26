<?php

namespace App\Application\Services\Measure;

use App\Domain\BusinessModels\Measure;

class MeasureDTO 
{
    public ?int $id;
    public ?string $name;
    public ?string $created_at;

    public function __construct(Measure $model)
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
