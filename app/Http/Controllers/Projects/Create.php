<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreRequest;
use App\Application\UseCases\Commands\Project\Create\Command;
use App\Application\UseCases\Commands\Project\Create\Handler;
use App\Domain\Repositories\Exceptions\CreateModelFailedException;

class Create extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Handler $handler)
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
