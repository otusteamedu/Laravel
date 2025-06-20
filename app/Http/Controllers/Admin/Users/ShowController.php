<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchUserById\Query;
use App\Services\Queries\FetchUserById\Fetcher;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали пользователя
     */
    public function show(Fetcher $fetcher, string $userId): View
    {
        try {
            $query = new Query((int)$userId);
            $user = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return view('admin.users.show', compact('user'));
    }
}
