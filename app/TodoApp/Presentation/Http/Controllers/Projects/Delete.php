<?php

namespace App\TodoApp\Presentation\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Application\UseCases\Commands\Project\Delete\Command;
use App\TodoApp\Application\UseCases\Commands\Project\Delete\Handler;

class Delete extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(Handler $handler, int $projectId)
    {
        try {
            $handler->handle(
                new Command(
                    id: $projectId,
                )
            );

            return redirect(route('projects.index'))->with('success', 'Проект удален');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
