<?php

namespace App\Http\PageDataProviders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\PageDataProviders\AbstractPageDataProvider;

class TodoListDataProfider extends AbstractPageDataProvider
{
    public $todos;
    public string $title;
    public function __construct(Request $request, User $user)
    {
        $this->todos = Todo::query()
            ->member($user)
            ->with('comments')
            ->with('project')
            ->with('status')
            ->with('todoUsers')
            ->get();

        $this->title = self::generateTitle();
    }

    protected function generateTitle(): string
    {
        return 'ToDo - Мои задачи';
    }
}
