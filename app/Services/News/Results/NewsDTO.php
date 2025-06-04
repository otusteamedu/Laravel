<?php
declare(strict_types=1);

namespace App\Services\News\Results;

use Carbon\Carbon;

final readonly class NewsDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public bool $isDraft,
        public ?string $thumbnail,
        public ?AuthorDTO $author,
        public ?CategoryDTO $category,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
        public ?Carbon $publishedAt,
    )
    {
    }
}
