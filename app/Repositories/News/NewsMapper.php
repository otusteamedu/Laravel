<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\News;

use App\Domain\News\Entities\News as DomainNews;
use App\Domain\News\Entities\Category as DomainCategory;
use App\Domain\User\Entities\User as DomainUser;
use App\Models\News as EloquentNews;
use App\Models\Category as EloquentCategory;
use App\Models\User as EloquentUser;
use App\Domain\User\ValueObjects\Roles;

class NewsMapper
{
    public static function toModel(DomainNews $news): EloquentNews
    {
        $model = EloquentNews::find($news->getId()) ?? new EloquentNews();

        if ($news->getId()) {
            $model->{$model->getColumnName('id')} = $news->getId();
        }

        $model->{$model->getColumnName('title')} = $news->getTitle();
        $model->{$model->getColumnName('content')} = $news->getContent();
        $model->{$model->getColumnName('category_id')} = $news->getCategory()->getId();
        $model->{$model->getColumnName('author_id')} = $news->getAuthor()->getId();
        $model->{$model->getColumnName('published_at')} = $news->getPublishedAt();
        $model->{$model->getColumnName('is_draft')} = $news->isDraft();
        $model->{$model->getColumnName('thumbnail')} = $news->getThumbnail();

        if ($news->getCreatedAt()) {
            $model->{$model->getColumnName('created_at')} = $news->getCreatedAt();
        }

        if ($news->getUpdatedAt()) {
            $model->{$model->getColumnName('updated_at')} = $news->getUpdatedAt();
        }

        return $model;
    }

    /**
     * Преобразовать Eloquent-модель News в доменную сущность.
     */
    public static function toEntity(EloquentNews $model): DomainNews
    {
        // Получаем связанные Eloquent-модели
        $eloquentAuthor = $model->author ?? EloquentUser::find($model->getAuthorId());
        $eloquentCategory = $model->category ?? EloquentCategory::find($model->getCategoryId());

        $roles = $eloquentAuthor->relationLoaded('roles')
            ? $eloquentAuthor->roles->pluck('slug')->all()
            : $eloquentAuthor->roles()->pluck('slug')->all();

        // Преобразуем их в доменные сущности (передаём все необходимые параметры)
        $author = new DomainUser(
            $eloquentAuthor->id,
            $eloquentAuthor->name,
            $eloquentAuthor->email,
            $eloquentAuthor->password,
            new Roles($roles),
            $eloquentAuthor->created_at ? $eloquentAuthor->created_at->toDateTimeImmutable() : null,
            $eloquentAuthor->updated_at ? $eloquentAuthor->updated_at->toDateTimeImmutable() : null,
            $eloquentAuthor->email_verified_at ? $eloquentAuthor->email_verified_at->toDateTimeImmutable() : null,
        );

        $category = new DomainCategory(
            $eloquentCategory->id,
            $eloquentCategory->name,
            $eloquentCategory->slug ?? '',
            $eloquentCategory->is_active ?? true,
            $eloquentCategory->sort ?? 0,
        );

        $news = new DomainNews(
            $model->{$model->getColumnName('id')},
            $author,
            $category,
            $model->{$model->getColumnName('title')},
            $model->{$model->getColumnName('content')},
            $model->{$model->getColumnName('published_at')} ? $model->{$model->getColumnName('published_at')}->toDateTimeImmutable() : null,
            $model->{$model->getColumnName('is_draft')},
            $model->{$model->getColumnName('thumbnail')},
            $model->{$model->getColumnName('created_at')} ? $model->{$model->getColumnName('created_at')}->toDateTimeImmutable() : null,
            $model->{$model->getColumnName('updated_at')} ? $model->{$model->getColumnName('updated_at')}->toDateTimeImmutable() : null,
        );

        return $news;
    }
}
