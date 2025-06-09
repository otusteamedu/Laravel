<?php

namespace App\Http\Controllers\ProjectUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\UseCases\Commands\ProjectUser\Left\Command;
use App\Services\UseCases\Commands\ProjectUser\Left\Handler;
use App\Services\UseCases\Commands\ProjectUser\Left\InviteNotFoundException;

class Left extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(int $projectId, int $userId, Handler $handler): RedirectResponse
    {
        try {
            $success = $handler->handle(new Command(
                projectId: $projectId,
                userId: $userId
            ));

            if ($success) {
                return redirect()->route('projects.index')->with('success', 'Успешно');
            } else {
                return redirect()->back()->with('error', 'Не получилось выйти из проекта');
            }
        } catch (InviteNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
