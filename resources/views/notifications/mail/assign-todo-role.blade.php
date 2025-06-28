@php
/**
 * @var App\Models\TodoRoleEnum $role
 * @var App\Services\Repositories\DTOs\UserDTO $user
 * @var App\Services\Repositories\Todo\TodoFetchDTO $todo
 * @var App\Services\Repositories\DTOs\ProjectDTO $project
 */
@endphp
@extends('layouts.mail-main')
@section('content')
<p>Вас назначили на роль <b>{{ $role->value }}</b></p>

<p>Уважаемый {{ $user->name }}!</p>
<p>
    Вас назначили на роль {{ $role->value }} 
    в задаче <a href="{{ route('project.todos.show', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}">{{ $todo->title }}</a> 
    проекта <a href="{{ route('projects.show', ['projectId' => $project->projectId]) }}">{{ $project->name }}</a>
</p>
@endsection
