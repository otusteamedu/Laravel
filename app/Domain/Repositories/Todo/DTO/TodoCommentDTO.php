<?php

namespace App\Domain\Repositories\Todo\DTO;

use Carbon\Carbon;


final readonly class TodoCommentDTO
{
    /**
     * @param int $todoId
     * @param int $authorId
     * @param string $comment
     * @param Carbon|null $created
     * @param Carbon|null $updated
     * @param int|null $commentId
     */
    public function __construct(
        public int     $todoId,
        public int     $authorId,
        public string  $comment,
        public ?Carbon $created = null,
        public ?Carbon $updated = null,
        public ?int    $commentId = null,
    ) {}
}
