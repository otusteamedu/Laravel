<?php
namespace App\Services\Users\Results;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class Fetcher
{
    /**
     * Преобразует пагинированную коллекцию пользователей в пагинированную коллекцию DTO
     *
     * @param LengthAwarePaginator $paginatedUsers
     * @return LengthAwarePaginator
     */
    public function fetch(LengthAwarePaginator $paginatedUsers): LengthAwarePaginator
    {
        $users = $paginatedUsers->items();

        $userDTOs = array_map(function ($user) {
            return new UserDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                created_at: $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
                updated_at: $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : null,
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
