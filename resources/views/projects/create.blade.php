@extends('layouts.main')
@section('title', 'ToDo: Создание проекта')
@section('content')
    <div class="col-12">
        <h1 class="mb-4">Создание проекта</h1>
        <form method="POST" action="{{ route('projects.store') }}" autocomplete="off">
            @csrf
            @include('projects.partials.form-fields')
            <div class="col-12 my-2 text-end">
                <button class="btn btn-outline-primary">Создать</button>
            </div>
        </form>
    </div>
@endsection
