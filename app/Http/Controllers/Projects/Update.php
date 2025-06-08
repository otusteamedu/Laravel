<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Project\UpdateRequest;
use App\Services\UseCases\Commands\Project\Update\Command;
use App\Services\UseCases\Commands\Project\Update\Handler;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Update extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(int $projectId, UpdateRequest $request, Handler $handler): RedirectResponse
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    id: $projectId,
                    name: $data['name'],
                    description: $data['description'],
                )
            );

            return redirect(route('projects.show', ['projectId' => $projectId]))->with('success', 'Проект обновлен');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
