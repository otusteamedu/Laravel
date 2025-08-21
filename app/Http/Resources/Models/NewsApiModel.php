<?php

declare(strict_types=1);

namespace App\Http\Resources\Models;

class NewsApiModel
{
    public function __construct(
        public int $id,
        public string $name,
        public string $text,
        public string $preview,
        public string $link,
        public string $photo,
        public ?string $created_at,
        public ?string $updated_at,
        public ?string $create_at,
        public int $user_id,
    ) {}
}
