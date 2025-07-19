<?php

namespace App\BusinessModels;

interface BusinessModelsInterface 
{
    public function toArray(): array;
    public function toArrayForCreat(): array;
}
