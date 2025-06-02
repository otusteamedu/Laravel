<?php
declare(strict_types=1);

namespace App\Services\User\Results;

use App\Models\User;
class Fetcher
{

    /**
     * @param User[]|User $users
     *
     * @return Result
     */
    public function fetch(array|User $users): Result|UserDTO
    {
        if (is_array($users)) {
            $userDTOs = array_map(fn (User $user) => $this->wrapItem($user), $users);

            return new Result($userDTOs);
        } else {
            return $this->wrapItem($users);
        }
    }


    /**
     * @param User $user
     *
     * @return UserDTO
     */
    private function wrapItem(User $user): UserDTO {
        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
        );
    }
}
