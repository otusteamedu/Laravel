<?php

namespace App\Application\Services\Product;

use App\Domain\BusinessModels\Product;

class ProductDTO 
{
    public ?int $id;
    public ?string $name;
    public ?string $description;
    public ?string $created_at;

    public function __construct(Product $model)
    {
        $this->id = $model->getId();
        $this->name = $model->getName()->getValue();
        $this->description = $model->getDescription()->getValue();
        $this->created_at = $model->getCreatedAt();
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
