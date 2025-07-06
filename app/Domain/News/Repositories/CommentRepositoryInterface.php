<?php

declare(strict_types=1);

namespace App\Domain\News\Repositories;

use App\Domain\News\Entities\Comment;

interface CommentRepositoryInterface
{
    /**
     * Получить все комментарии.
     * @return Comment[]
     */
    public function fetchAll(): array;

    /**
     * Получить комментарии по ID новости.
     * @param int $newsId
     * @return Comment[]
     */
    public function fetchByNewsId(int $newsId): array;

    /**
     * Найти комментарий по ID.
     * @param int $id
     * @return Comment|null
     */
    public function find(int $id): ?Comment;

    /**
     * Сохранить комментарий.
     * @param Comment $comment
     * @return bool
     */
    public function save(Comment $comment): bool;

    /**
     * Удалить комментарий.
     * @param Comment $comment
     * @return bool|null
     */
    public function delete(Comment $comment): ?bool;

    /**
     * Получить комментарии по массиву ID.
     * @param int[] $ids
     * @return Comment[]
     */
    public function findByIds(array $ids): array;

    /**
     * Получить ответы на комментарий (дочерние комментарии).
     * @param int $parentId
     * @return Comment[]
     */
    public function fetchReplies(int $parentId): array;
}
