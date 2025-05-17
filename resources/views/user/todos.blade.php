@php
/**
 * @var App\Http\PageDataProviders\TodoListDataProfider $data
 */
@endphp
@extends('layouts.main')
@section('title', 'ToDo: Мои задачи.')

@section('content')
    @foreach($data->todos as $todo)
        <div class="card mb-3" style="border-color: #{{ $todo->status->color }}">
            <div class="card-header d-flex" style="background-color: #{{ $todo->status->color }}">
                {{ $todo->status->name }}
                @if (!empty($todo->options['isHot']))
                    <i class="fa-solid fa-fire text-danger ms-auto" title="Важная"></i>
                @endif
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $todo->title }}</h5>
                <p class="card-text">{!! $todo->description !!}</p>
                <p class="card-text"><small class="text-muted">Срок {{ $todo->deadline->format("d.m.Y") }}</small></p>
            </div>
        </div>
    @endforeach
@endsection
