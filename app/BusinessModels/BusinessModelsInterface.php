<?php

namespace App\BusinessModels;

use App\EloquentModels\BaseModel;

interface BusinessModelsInterface 
{
    public function toArray(): array;
}
