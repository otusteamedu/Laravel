<?php

namespace App\Repositories;

use App\Dto\Message\StoreDto;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class MessagesRepository
{
    /**
     * @return Collection<array-key, Message>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'messages.all';
        $ttl = now()->addDay();
        $tag = 'message-list';

        if ($warmup) {
            $messages = Message::orderBy('id', 'desc')->limit(50)->get();
            Cache::tags($tag)->put($key, $messages, $ttl);
            return $messages;
        }

        $messages = Cache::tags($tag)->remember($key, $ttl, function () {
            return Message::orderBy('id', 'desc')->limit(50)->get();
        });
        
        return $messages;
    }

    /**
     * @return Message
     */
    public function add(StoreDto $storeDto): Message
    {
        $message = new Message();
        $message->content = $storeDto->content;
        $message->user_id = $storeDto->user_id;
        $message->save();

        Cache::tags('message-list')->flush();
        return $message;
    }

    public function warmupCache(): void
    {
        $this->fetchAll(true);
    }
}