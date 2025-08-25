<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;

class MeasureProductRecipe extends BaseModel implements BusinessModelsInterface
{
    private MeasureProductRecipeValue $value;
    private ?string $created_at;

    public function __construct(
        MeasureProductRecipeValue $value, 
        ?int $id = null, 
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->value = $value;
        $this->created_at = $created_at;
    }

    public function getMeasureProductRecipeValue(): MeasureProductRecipeValue 
    {
        return $this->value;
    }

    public function updateValue(MeasureProductRecipeValue $newValue): void 
    {
        if ($this->value === $newValue) {
            throw new NotValidItemDomainException("Новое значение совпадает со старым");
        }
        $this->value = $newValue;
    }

    public function getCreatedAt(): string 
    {
        return $this->created_at;
    }

    public function toArray(?string $lang = null): array 
    {
        if (is_null($this->getId())) {
            $array = [
                'value' => $this->getMeasureProductRecipeValue()->getValue(),
            ];
        } else {
            $array = [
                'id' => $this->getId(),
                'value' => $this->getMeasureProductRecipeValue()->getValue(),
                'created_at' => $this->getCreatedAt(),
            ];
        }
        return $array;
    }
}
