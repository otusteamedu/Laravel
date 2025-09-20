<?php

namespace App\Application\Actions\Translation;

use App\Domain\ValueObjects\Lang;

class TranslateAction implements TranslateActionInterface
{
    private TranslateActionInfrastructureInterface $infrastructure;

    public function __construct(
        TranslateActionInfrastructureInterface $infrastructure
    ) {
        $this->infrastructure = $infrastructure;
    }

    public function translate(
        string $langFrom, 
        string $langTo, 
        string $text
    ): array {
        $langFrom = new Lang($langFrom);
        $langTo = new Lang($langTo);
        $translation = $this->infrastructure->translate($langFrom, $langTo, $text);
        return (new TranslationDTO($translation))->toArray();
    }
}