<?php

namespace App\TodoApp\Presentation\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;
use App\TodoApp\Presentation\Http\Requests\Project\StoreRequest;
use App\TodoApp\Application\UseCases\Commands\Project\Create\Command;
use App\TodoApp\Application\UseCases\Commands\Project\Create\Handler;

class Create extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('todo-app::projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Handler $handler): RedirectResponse
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    name: $data['name'],
                    description: $data['description'],
                    userId: $data['user_id'],
                )
            );

            return redirect(route('projects.show', ['projectId' => $result->id]))->with('success', 'Проект добавлен');
        } catch (CreateModelFailedException $exception) {
            return redirect()->route('projects.index')->withInput()->with('error', $exception->getMessage());
        }
    }
}
