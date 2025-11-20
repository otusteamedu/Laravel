<?php

namespace App\DTO;

use App\Models\Post;

class PostDTO
{
    public function __construct(
        public string $title,
        public string $text
    ) {

    }

    static public function fromEloquent(Post $data): PostDTO
    {
        return new static($data->title, $data->text);
    }
}
