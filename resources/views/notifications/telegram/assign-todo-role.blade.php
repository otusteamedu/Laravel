@php
/**
 * @var string $role
 * @var string $userName
 * @var App\Services\Repositories\DTOs\ProjectDTO $project
 * @var App\Services\Repositories\Todo\TodoFetchDTO $todo
 */
@endphp

Вас назначили на роль <b>{{ $role }}</b>

Уважаемый {{ $userName }}!

Вас назначили на роль {{ $role }} 
в задаче <a href="{{ route('project.todos.show', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}">{{ $todo->title }}</a> 
проекта <a href="{{ route('projects.show', ['projectId' => $project->projectId]) }}">{{ $project->name }}</a>
