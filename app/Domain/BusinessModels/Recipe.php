<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Recipe\RecipeArea;
use App\Domain\ValueObjects\Recipe\RecipeCategory;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;

class Recipe extends BaseModel implements BusinessModelsInterface
{
    private RecipeName $name;
    private RecipeInstruction $instruction;
    private string $apiId;
    private string $alternate;
    private Category $category;
    private Area $area;
    private ?string $created_at;

    public function __construct(
        RecipeName $name,
        RecipeInstruction $instruction,
        Lang $lang,
        ?string $apiId = null,
        ?string $alternate = null,
        ?Category $category = null,
        ?Area $area = null,
        ?int $id = null,
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->instruction = $instruction;
        $this->lang = $lang;
        $this->apiId = $apiId;
        $this->alternate = $alternate;
        $this->category = $category;
        $this->area = $area;
        $this->created_at = $created_at;
    }

    public function getName(): RecipeName
    {
        return $this->name;
    }

    public function getInstruction(): RecipeInstruction
    {
        return $this->instruction;
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function getAlternate(): string
    {
        return $this->alternate;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getArea(): Area
    {
        return $this->area;
    }

    public function rename(RecipeName $newName): void
    {
        if ($this->name === $newName) {
            throw new NotValidItemDomainException("Новое название совпадает со старым");
        }
        $this->name = $newName;
    }

    public function updateInstruction(RecipeInstruction $newInstruction): void
    {
        $this->instruction = $newInstruction;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function toArray(?string $lang = null): array
    {
        if (is_null($this->getId())) {
            $array = [
                'name_' . $this->getLang()->getValue() => $this->getName()->getValue(),
                'instruction_' . $this->getLang()->getValue() => $this->getInstruction()->getValue(),
            ];
        } else {
            $array = [
                'id' => $this->getId(),
                'name_' . ($lang ?? $this->getLang()->getValue()) => $this->getName()->getValue(),
                'instruction_' . $this->getLang()->getValue() => $this->getInstruction()->getValue(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
