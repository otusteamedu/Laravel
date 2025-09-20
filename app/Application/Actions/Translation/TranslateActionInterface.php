<?php

namespace App\Application\Actions\Translation;

interface TranslateActionInterface
{
    public function translate(string $langFrom, string $langTo, string $text): array;
}