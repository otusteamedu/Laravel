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
            $handler->handle(new Command(
                projectId: $projectId,
                userId: $userId
            ));

            return redirect()->route('projects.index')->with('success', 'Успешно');
        } catch (InviteNotFoundException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
