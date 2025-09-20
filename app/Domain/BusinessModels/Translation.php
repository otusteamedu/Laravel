<?php

namespace App\Domain\BusinessModels;

use App\Domain\ValueObjects\Lang;

class Translation extends BaseModel implements BusinessModelsInterface
{
    private string $textFrom;
    private Lang $langFrom;
    private Lang $langTo;
    private string $textTo;

    public function __construct(
        string $textFrom,
        Lang $langFrom,
        Lang $langTo,
        string $textTo,
    ) {
        $this->textFrom = $textFrom;
        $this->langFrom = $langFrom;
        $this->langTo = $langTo;
        $this->textTo = $textTo;
    }

    public function getLangFrom(
        //
    ): Lang {
        return $this->langFrom;
    }

    public function getLangTo(
        //
    ): Lang {
        return $this->langTo;
    }

    public function getTextFrom(
        //
    ): string {
        return $this->textFrom;
    }

    public function getTextTo(
        //
    ): string {
        return $this->textTo;
    }

    public function toArray(
        //
    ): array {
        return [
            'textFrom' => $this->getTextFrom(),
            'langFrom' => $this->getLangFrom()->getValue(),
            'langTo' => $this->getLangTo()->getValue(),
            'textTo' => $this->getTextTo(),
        ];
    }
}