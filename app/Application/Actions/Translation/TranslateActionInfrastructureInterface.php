<?php

namespace App\Application\Actions\Translation;

use App\Domain\BusinessModels\Translation;
use App\Domain\ValueObjects\Lang;

interface TranslateActionInfrastructureInterface 
{
    public function translate(Lang $langFrom, Lang $langTo, string $text): Translation;
}