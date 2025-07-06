<?php

namespace App\Dto\Message;

class StoreDto
{
    public function __construct(public string $content, public int $user_id) {}
}