<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;

class Recipe extends BaseModel implements BusinessModelsInterface
{
    private RecipeName $name;
    private RecipeInstruction $instruction;
    private string $apiId;
    private string $alternate;
    private int $categoryId;
    private int $areaId;
    private ?string $created_at;

    public function __construct(
        RecipeName $name, 
        RecipeInstruction $instruction,
        Lang $lang,
        ?string $apiId = null,
        ?string $alternate = null,
        ?int $categoryId = null,
        ?int $areaId = null,
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->instruction = $instruction;
        $this->lang = $lang;
        $this->apiId = $apiId;
        $this->alternate = $alternate;
        $this->categoryId = $categoryId;
        $this->areaId = $areaId;
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

    public function getCategoryId(): int 
    {
        return $this->categoryId;
    }

    public function getAreaId(): int 
    {
        return $this->areaId;
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
