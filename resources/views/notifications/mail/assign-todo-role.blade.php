@php
/**
 * @var string $role
 * @var App\Domain\Repositories\User\DTO\UserDTO $user
 * @var App\Domain\Repositories\Todo\DTO\TodoFetchDTO $todo
 * @var App\Domain\Repositories\Project\DTO\ProjectDTO $project
 */
@endphp
@extends('layouts.mail-main')
@section('content')
<p>Вас назначили на роль <b>{{ $role }}</b></p>

<p>Уважаемый {{ $user->name }}!</p>
<p>
    Вас назначили на роль {{ $role }} 
    в задаче <a href="{{ route('project.todos.show', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}">{{ $todo->title }}</a> 
    проекта <a href="{{ route('projects.show', ['projectId' => $project->projectId]) }}">{{ $project->name }}</a>
</p>
@endsection
