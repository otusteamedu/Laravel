<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Exceptions\Users\UserNotFoundException;
use App\Services\UseCases\Queries\FetchUserById\Fetcher;
use App\Services\UseCases\Queries\FetchUserById\Query;
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
