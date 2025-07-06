<?php

namespace App\Http\Controllers\Admin\Users;

use App\Application\UseCases\User\Queries\FetchUserById\Fetcher;
use App\Application\UseCases\User\Queries\FetchUserById\Query;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали пользователя
     */
    public function __invoke(Fetcher $fetcher, string $userId): View
    {
        try {
            $query = new Query((int)$userId);
            $user = $fetcher->fetch($query);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return view('admin.users.show', compact('user'));
    }
}
