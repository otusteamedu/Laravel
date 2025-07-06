<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Comments;

use App\Domain\News\Entities\Comment as DomainComment;
use App\Domain\News\Repositories\CommentRepositoryInterface;
use App\Models\Comment as EloquentComment;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @return DomainComment[]
     */
    public function fetchAll(): array
    {
        $models = EloquentComment::with(['author', 'news', 'parent'])->get();
        return array_map([CommentMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $newsId
     * @return DomainComment[]
     */
    public function fetchByNewsId(int $newsId): array
    {
        $models = EloquentComment::with(['author', 'parent'])
                                 ->where('news_id', $newsId)
                                 ->orderBy('created_at')
                                 ->get();

        return array_map([CommentMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $id
     * @return DomainComment|null
     */
    public function find(int $id): ?DomainComment
    {
        $model = EloquentComment::with(['author', 'news', 'parent'])->find($id);
        return $model ? CommentMapper::toEntity($model) : null;
    }

    /**
     * @param DomainComment $comment
     * @return bool
     */
    public function save(DomainComment $comment): bool
    {
        $model = CommentMapper::toModel($comment);
        return $model->save();
    }

    /**
     * @param DomainComment $comment
     * @return bool|null
     */
    public function delete(DomainComment $comment): ?bool
    {
        $model = EloquentComment::find($comment->getId());
        return $model ? $model->delete() : null;
    }

    /**
     * @param int[] $ids
     * @return DomainComment[]
     */
    public function findByIds(array $ids): array
    {
        $models = EloquentComment::with(['author', 'news', 'parent'])
                                 ->whereIn('id', $ids)
                                 ->get();
        return array_map([CommentMapper::class, 'toEntity'], $models->all());
    }

    /**
     * @param int $parentId
     * @return DomainComment[]
     */
    public function fetchReplies(int $parentId): array
    {
        $models = EloquentComment::with(['author', 'news', 'parent'])
                                 ->where('parent_id', $parentId)
                                 ->orderBy('created_at')
                                 ->get();
        return array_map([CommentMapper::class, 'toEntity'], $models->all());
    }
}
