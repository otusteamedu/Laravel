@php
/**
 * @var int|null $id
 */
@endphp
@extends('layouts.main')
@section('title', 'ToDo: Редактирование проекта')
@section('content')
    <div class="col-12">
        <h1 class="mb-4">Редактирование проекта</h1>
        <form method="POST" action="{{ route('projects.update', ['projectId' => $id]) }}" autocomplete="off">
            @csrf
            @method('put')

            @include('projects.partials.form-fields')
            
            <div class="col-12 my-2 text-end">
                <button class="btn btn-outline-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
