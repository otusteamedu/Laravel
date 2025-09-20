<?php

namespace App\Application\Services\Category;

use App\Domain\BusinessModels\Category;

class CategoryDTO 
{
    public ?int $id;
    public ?int $apiId;
    public ?string $name;
    public ?string $created_at;

    public function __construct(Category $model)
    {
        $this->id = $model->getId();
        $this->apiId = $model->getApiId();
        $this->name = $model->getName()->getValue();
        $this->created_at = $model->getCreatedAt();
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'api_id' => $this->apiId,
            'name' => $this->name,
            'created_at' => $this->created_at
        ];
    }
}
