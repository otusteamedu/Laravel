<?php

namespace App\TodoApp\Presentation\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\StoreRequest;
use App\Services\UseCases\Commands\Todo\Create\Command;
use App\Services\UseCases\Commands\Todo\Create\Handler;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;

class Create extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(StoreRequest $request, Handler $handler)
    {
        $data = $request->validated();

        try {
            $handler->handle(
                new Command(
                    title: $data['title'],
                    authorId: $data['author_id'],
                    projectId: $data['project_id'],
                    statusId: $data['status_id'],
                    description: $data['description'],
                    deadline: $data['deadline'],
                    options: $data['options'] ?? []
                )
            );

            return redirect()->back()->with('success', 'Задачи для проекта добавлена');
        } catch (CreateModelFailedException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
