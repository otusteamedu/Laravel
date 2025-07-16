<?php

namespace App\TodoApp\Presentation\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Presentation\Http\Requests\Project\UpdateRequest;
use App\TodoApp\Application\UseCases\Commands\Project\Update\Command;
use App\TodoApp\Application\UseCases\Commands\Project\Update\Handler;

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
