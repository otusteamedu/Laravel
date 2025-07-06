<?php
namespace App\Services;

use App\Dto\Message\StoreDto;
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
        $this->repository->add($storeDto);
    }
}