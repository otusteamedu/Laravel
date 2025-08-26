<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Comments;

use App\Domain\News\Entities\Comment as DomainComment;
use App\Domain\User\Entities\User as DomainUser;
use App\Domain\News\Entities\News as DomainNews;
use App\Models\Comment as EloquentComment;

class CommentMapper
{
    public static function toModel(DomainComment $comment): EloquentComment
    {
        /*$model = EloquentComment::find($comment->getId()) ?? new EloquentComment();
        $model->id = $comment->getId();
        $model->news_id = $comment->getNews()->getId();
        $model->author_id = $comment->getAuthor()->getId();
        $model->content = $comment->getContent();
        $model->parent_id = $comment->getParentId();
        $model->created_at = $comment->getCreatedAt();
        // Добавьте другие поля по необходимости
        return $model;*/
    }

    public static function toEntity(EloquentComment $model): DomainComment
    {
        // Преобразуем связанные сущности
        /*$author = new DomainUser(
            $model->author->id,
            $model->author->name,
            $model->author->email
        );
        $news = new DomainNews(
            $model->news->id,
            $author, // Можно передать null или получить автора новости, если нужно
            null,    // Категория не обязательна для комментария
            $model->news->title,
            $model->news->content
        );
        $parentId = $model->parent_id;

        return new DomainComment(
            $model->id,
            $news,
            $author,
            $model->content,
            $parentId,
            $model->created_at
        );*/
    }
}

