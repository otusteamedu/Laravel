<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Categories;

use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Services\CategorySlugGeneratorInterface;
use Illuminate\Support\Str;

class CategorySlugGenerator implements CategorySlugGeneratorInterface
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository) {}

    public function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $i = 1;
        while ($this->categoryRepository->existsBySlug($slug)) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }
        return $slug;
    }
}
