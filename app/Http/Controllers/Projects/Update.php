<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Project\UpdateRequest;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\UseCases\Commands\Project\Update\Command;
use App\Services\UseCases\Commands\Project\Update\Handler;
use App\Services\UseCases\Commands\Project\Update\ModelNotFoundException;

class Update extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectRepositoryInterface $projectRepository, int $projectId): View
    {
        $project = $projectRepository->find($projectId);

        if (!$project) {
            abort(404);
        }

        return view('projects.update', [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Handler $handler, int $projectId): RedirectResponse
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
