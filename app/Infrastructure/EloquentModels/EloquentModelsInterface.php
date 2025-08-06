<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;

interface EloquentModelsInterface 
{
    public function toBusinessModel(): ?BusinessBaseModel;
}
