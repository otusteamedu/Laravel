<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\Exceptions\UserNotFoundException;
use App\Services\Users\Handlers\DestroyHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить пользователя
     */
    public function __invoke(DestroyHandler $destroyHandler, string $userId)
    {
        try {
            $destroyHandler((int)$userId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь успешно удален");
    }
} 