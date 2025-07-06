<?php

namespace App\Repositories;

use App\Dto\Message\StoreDto;
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

    public function add(StoreDto $storeDto): void
    {
        $message = new Message();
        $message->content = $storeDto->content;
        $message->user_id = $storeDto->user_id;
        $message->save();
    }
}