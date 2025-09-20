<?php

namespace App\Application\Actions\Translation;

use App\Domain\BusinessModels\Translation;

class TranslationDTO
{
    private string $textFrom;
    private string $langFrom;
    private string $langTo;
    private string $textTo;

    public function __construct(
        Translation $translation
    ) {
        $this->textFrom = $translation->getTextFrom();
        $this->langFrom = $translation->getLangFrom()->getValue();
        $this->langTo = $translation->getLangTo()->getValue();
        $this->textTo = $translation->getTextTo();
    }

    public function toArray(
        //
    ): array {
        return [
            'textFrom' => $this->textFrom,
            'langFrom' => $this->langFrom,
            'langTo' => $this->langTo,
            'textTo' => $this->textTo,
        ];
    }
}