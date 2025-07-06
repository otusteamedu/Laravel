<?php

namespace App\Http\Controllers\Admin\Users;

use App\Application\UseCases\User\Commands\DeleteUser\Command;
use App\Application\UseCases\User\Commands\DeleteUser\Handler;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyController extends Controller
{
    /**
     * Удалить пользователя
     */
    public function __invoke(Handler $handler, string $userId): RedirectResponse
    {
        try {
            $command = new Command((int)$userId);
            $handler->handle($command);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь успешно удален");
    }
}
