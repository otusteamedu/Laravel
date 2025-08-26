<?php

namespace App\Application\Services\Recipe;

use App\Domain\BusinessModels\Recipe;

class RecipeDTO 
{
    public ?int $id;
    public ?int $apiId;
    public ?string $name;
    public ?string $alternate;
    public ?array $category;
    public ?string $instruction;
    public ?array $area;
    public ?string $created_at;

    public function __construct(Recipe $model)
    {
        $this->id = $model->getId();
        $this->apiId = $model->getApiId();
        $this->name = $model->getName()->getValue();
        $this->alternate = $model->getAlternate();
        $this->category = $model->getCategory()->toArray();
        $this->instruction = $model->getInstruction()->getValue();
        $this->area = $model->getArea()->toArray();
        $this->created_at = $model->getCreatedAt();
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'api_id' => $this->apiId,
            'name' => $this->name,
            'alternate' => $this->alternate,
            'category' => $this->category,
            'instruction' => $this->instruction,
            'area' => $this->area,
            'created_at' => $this->created_at
        ];
    }
}
