<?php

namespace App\Application\UseCases\News\Commands\UpdateNews;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $name,
        public string $text,
        public string $link,
        public string $preview,
        public string $photo,
        public ?int $user_id,
        public ?\DateTimeImmutable $createAt,
    ) {
    }
}
