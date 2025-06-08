<?php

namespace App\Http\Controllers\ProjectUsers;

use App\Models\ProjectRoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProjectUser\InviteRequest;
use App\Services\UseCases\Commands\ProjectUser\Invite\Command;
use App\Services\UseCases\Commands\ProjectUser\Invite\Handler;
use App\Services\Repositories\Exceptions\CreateModelFailedException;

class Invite extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(int $projectId, InviteRequest $request, Handler $handler): RedirectResponse
    {
        $validatesd = $request->validated();

        try {
            $handler->handle(
                new Command(
                    projectId: $validatesd['project_id'],
                    userId: $validatesd['user_id'],
                    roles: [ProjectRoleEnum::MEMBER],

                )
            );

            return redirect()->back()->with('success', 'Пользователь приглашен к участию в проекте');
        } catch (CreateModelFailedException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
