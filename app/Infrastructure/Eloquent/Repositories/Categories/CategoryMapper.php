<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Categories;

use App\Domain\News\Entities\Category as DomainCategory;
use App\Models\Category as EloquentCategory;

class CategoryMapper
{
    public static function toModel(DomainCategory $category): EloquentCategory
    {
        $model = EloquentCategory::find($category->getId()) ?? new EloquentCategory();

        if ($category->getId()) {
            $model->{$model->getColumnName('id')} = $category->getId();
        }

        $model->{$model->getColumnName('name')} = $category->getName();
        $model->{$model->getColumnName('slug')} = $category->getSlug();
        $model->{$model->getColumnName('is_active')} = $category->isActive(); // или getIsActive()
        $model->{$model->getColumnName('sort')} = $category->getSort();

        return $model;
    }

    public static function toEntity(EloquentCategory $model): DomainCategory
    {
        return new DomainCategory(
            $model->{$model->getColumnName('id')},
            $model->{$model->getColumnName('name')},
            $model->{$model->getColumnName('slug')},
            (bool)($model->{$model->getColumnName('is_active')} ?? true),
            (int)($model->{$model->getColumnName('sort')} ?? 0)
        );
    }
}
