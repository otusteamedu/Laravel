<?php

namespace App\VO;

class PostTitle
{
    private $title;
    public function __construct(
        string $title,
    ) {
        if (empty($title)) {
            throw new \InvalidArgumentException("PostTitle cannot be an empty string");
        }

        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
