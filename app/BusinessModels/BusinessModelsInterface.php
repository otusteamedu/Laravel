<?php

namespace App\BusinessModels;

use App\EloquentModels\BaseModel;

interface BusinessModelsInterface 
{
    public static function createFromEloquentModel(BaseModel $EloquentModel): self;

    public function toArray(): array;
}
