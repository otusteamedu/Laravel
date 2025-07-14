<?php

declare(strict_types=1);

namespace App\Http\Resources\Models;

class NewsApiModel
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public bool $is_draft,
        public ?string $thumbnail,
        public ?string $created_at,
        public ?string $updated_at,
        public ?string $published_at,
        public ?AuthorApiModel $author,
        public ?CategoryApiModel $category,
    ) {}
}
