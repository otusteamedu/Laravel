<?php

namespace App\Domain\Factories\Tag;

use App\Domain\BusinessModels\Tag;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Tag\TagName;

class AreaFactory
{
    public static function make(string $name, string $lang): Tag
    {
        $tagName = new TagName($name);
        $lang = new Lang($lang);

        return new Tag($tagName, $lang);
    }
}
