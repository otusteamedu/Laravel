<?php

namespace App\Services\Queries\FetchAllUsers;

use App\Repositories\Users\UserRepositoryInterface;
use App\Services\DTO\Users\UserDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function fetch(Query $query): LengthAwarePaginator
    {
        $paginatedUsers = $this->userRepository->getAllPaginated($query->perPage);
        $users = $paginatedUsers->items();

        $userDTOs = array_map(function ($user) {
            return new UserDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                createdAt: $user->created_at,
                updatedAt: $user->updated_at,
                emailVerifiedAt: $user->email_verified_at,
            );
        }, $users);

        $paginator = new LengthAwarePaginator(
            $userDTOs,
            $paginatedUsers->total(),
            $paginatedUsers->perPage(),
            $paginatedUsers->currentPage(),
            ['path' => $paginatedUsers->path()]
        );

        return $paginator->withQueryString();
    }
} 