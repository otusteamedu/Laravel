<?php

namespace App\DTO;

use App\Models\Post;
use App\Models\User;
use App\VO\PostText;
use App\VO\PostTitle;

class CreatePostRequestDTO
{
    public function __construct(
        public PostTitle $title,
        public PostText $text,
        public User $author,
    ) {

    }
}
