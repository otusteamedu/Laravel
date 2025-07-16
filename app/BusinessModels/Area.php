<?php

namespace App\BusinessModels;

use App\EloquentModels\BaseModel as EloquentBaseModel;

class Area extends BaseModel implements BusinessModelsInterface
{
    private string $name;
    private ?string $created_at;

    public function __construct(?int $id = null, string $name, ?string $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName(string $name) 
    {
        $this->name = $name;
    }

    public function getCreatedAt(): string 
    {
        return $this->created_at;
    }

    public static function createFromEloquentModel(EloquentBaseModel $EloquentModel): self 
    {
        return new self(id:$EloquentModel->getId(), name:$EloquentModel->getName(), created_at:$EloquentModel->getCreatedAt());
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'name' . config('app.locale') => $this->name,
            'created_at' => $this->created_at
        ];
    }
}
