<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\Handlers\ShowHandler;
use App\Services\Users\Exceptions\UserNotFoundException;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{

    public function __invoke(ShowHandler $handler, string $userId): View
    {
        try {
            $user = $handler((int)$userId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return view('admin.users.show', compact('user'));
    }
}
