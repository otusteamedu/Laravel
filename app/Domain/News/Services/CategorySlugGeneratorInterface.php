<?php

declare(strict_types=1);

namespace App\Domain\News\Services;

interface CategorySlugGeneratorInterface
{
    public function generateUniqueSlug(string $name): string;
}
