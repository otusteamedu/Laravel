<?php

namespace App\EloquentModels;

use App\BusinessModels\BaseModel as BusinessBaseModel;

interface EloquentModelsInterface 
{
    public function toBusinessModel(): BusinessBaseModel;
}
