<?php
namespace App\Services;

use App\Dto\Message\StoreDto;
use App\Events\MessageEvent;
use App\Repositories\MessagesRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Message;

class MessagesService
{
    public function __construct(
        private MessagesRepository $repository,
    ) {}

    /**
     * @return Collection<array-key, Message>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    public function add(StoreDto $storeDto): void
    {
        $message = $this->repository->add($storeDto);
        $userName = $message->getUser()->name; 
        MessageEvent::dispatch($storeDto->content, date('d.m.Y H:i'), $userName);
    }
}