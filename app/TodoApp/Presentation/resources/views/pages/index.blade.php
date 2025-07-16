@extends('todo-app::layouts.main')


@section('content')
    <div class="col-12 d-flex flex-column flex-md-row gap-2 gap-md-4">
        <div class="d-flex flex-column w-100">
            <h1>Обретите ясность.</h1>
            <div class="">Упростите жизнь себе и своей команде, используя менеджер задач и список дел</div>
        </div>
        <div class="w-100">
            <img src="{{ Vite::asset('resources/images/todo.webp') }}" class="w-100" alt="ToDo" />
        </div>
    </div>
@endsection