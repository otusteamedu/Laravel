<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessagesRepository
{
    /**
     * @return Collection<array-key, Message>
     */
    public function fetchAll(): Collection
    {
        $messages = Message::orderBy('id', 'desc')->limit(50)->get();
        return $messages;
    }
}