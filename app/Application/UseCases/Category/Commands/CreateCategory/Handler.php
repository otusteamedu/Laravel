<?php

namespace App\Application\UseCases\Category\Commands\CreateCategory;

use App\Application\UseCases\Category\DTO\CategoryDTO;
use App\Domain\News\Entities\Category as DomainCategory;
use App\Domain\News\Exceptions\CategoryAlreadyExistsException;
use App\Domain\News\Exceptions\CategorySaveException;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Services\CategorySlugGeneratorInterface;

class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CategorySlugGeneratorInterface $slugGenerator
    ) {
    }

    public function handle(Command $command): CategoryDTO
    {
        if ($this->categoryRepository->existsByName($command->name)) {
            throw new CategoryAlreadyExistsException($command->name);
        }

        $slug = $this->slugGenerator->generateUniqueSlug($command->name);

        $category = new DomainCategory(
            id: null, // id присвоит база данных
            name: $command->name,
            slug: $slug, //$command->slug ?? $this->generateSlug($command->name),
            isActive: $command->isActive,
            sort: $command->sort,
        );

        try {
            $domainCategory = $this->categoryRepository->save($category);
        } catch (\Exception) {
            throw new CategorySaveException("Не удалось сохранить категорию '{$command->name}'");
        }

        return new CategoryDTO(
            id: $domainCategory->getId(),
            name: $domainCategory->getName(),
            slug: $domainCategory->getSlug(),
            isActive: $domainCategory->isActive(),
            sort: $domainCategory->getSort(),
        );
    }

    /**
     * Генерация slug из имени (можно вынести в отдельный сервис)
     */
  /*  private function generateSlug(string $name): string
    {
        return \Str::slug($name); // Если используете Laravel, иначе реализуйте свой способ
    }*/
}

