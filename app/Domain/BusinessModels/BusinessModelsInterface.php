<?php

namespace App\Domain\BusinessModels;

interface BusinessModelsInterface 
{
    public function toArray(?string $lang): array;
}
