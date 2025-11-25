<?php

namespace App\VO;

class NotificationText
{
    private $text;
    public function __construct(
        string $text,
    ) {
        if (empty($text)) {
            throw new \InvalidArgumentException("NotificationText cannot be an empty string");
        }

        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
