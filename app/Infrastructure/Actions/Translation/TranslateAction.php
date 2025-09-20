<?php

namespace App\Infrastructure\Actions\Translation;

use App\Application\Actions\Translation\TranslateActionInfrastructureInterface;
use App\Domain\BusinessModels\Translation;
use App\Domain\ValueObjects\Lang;
use Stronger21012\Autotranslator\Services\Translation\TranslatorInterface;

class TranslateAction implements TranslateActionInfrastructureInterface
{
    private TranslatorInterface $autoTranslator;

    public function __construct(
        TranslatorInterface $autoTranslator
    ) {
        $this->autoTranslator = $autoTranslator;
    }

    public function translate(
        Lang $langFrom, 
        Lang $langTo, 
        string $text
    ): Translation {
        $textTo = $this->autoTranslator->translate($text, $langFrom->getValue(), $langTo->getValue());
        return new Translation(
            textFrom: $text,
            langFrom: $langFrom,
            langTo: $langTo,
            textTo: $textTo
        );
    }
}