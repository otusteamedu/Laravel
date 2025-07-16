<?php

namespace App\TodoApp\Presentation\Http\Controllers\ProjectUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\UseCases\Commands\ProjectUser\Join\Command;
use App\Services\UseCases\Commands\ProjectUser\Join\Handler;
use App\Services\UseCases\Commands\ProjectUser\Join\InviteNotFoundException;

class Join extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(int $projectId, int $userId, Handler $handler): RedirectResponse
    {
        try {
            $handler->handle(new Command(
                projectId: $projectId,
                userId: $userId
            ));

            return redirect()->back()->with('success', 'Успешно');
        } catch (InviteNotFoundException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
