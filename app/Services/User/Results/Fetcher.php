<?php
declare(strict_types=1);

namespace App\Services\User\Results;

use App\Models\User;
use App\Services\User\Results\User as ResultsUser;

class Fetcher
{

    /**
     * @param User[]|User $users
     *
     * @return UserItems|ResultsUser
     */
    public function fetch(array|User $users): UserItems|ResultsUser
    {
        if (is_array($users)) {
            $userDTOs = array_map(fn (User $user) => $this->wrapItem($user), $users);

            return new UserItems($userDTOs);
        } else {
            return $this->wrapItem($users);
        }
    }


    /**
     * @param User $user
     *
     * @return ResultsUser
     */
    private function wrapItem(User $user): ResultsUser {
        return new ResultsUser(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            subscribedNews: $user->subscribed_news
        );
    }
}
