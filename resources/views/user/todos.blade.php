@extends('layouts.main')
@section('title', 'ToDo - Профиль пользователя')

@php
    $todoKeys = array_keys($todos);
    
    $arr = array_merge($todoKeys, $todoKeys, $todoKeys);
    $items = array_map(function($k) use ($arr) {
        return $arr[$k];
    }, array_rand(($arr), rand(6, 12)))
@endphp

@section('content')
    @foreach($items as $todo)
        <div @class(["card", "border-$todo", "mb-3"])>
            <div @class([
                'card-header',
                'd-flex',
                "bg-$todo",
                'text-dark' => $todo !== 'success',
                'text-light' => $todo === 'success',
            ])>
                {{ $todos[$todo] }}
                @if((bool)random_int(0, 1))
                <i class="fa-solid fa-fire text-danger ms-auto" title="Важная"></i>
                @endif
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ fake()->sentence(2) }}</h5>
                <p class="card-text">{{ fake()->paragraph(rand(1,3)) }}</p>
                @switch($todo)
                    @case('warning')
                        <p class="card-text"><small class="text-muted">Создана {{ fake()->dateTimeBetween('-1 month', 'now')->format("d.m.Y в H:i") }}</small></p>
                        <a href="#" class="btn btn-info">В работу</a>
                        @break
                    @case('info')
                        <p class="card-text"><small class="text-muted">Последнее обновление {{ fake()->dateTimeBetween('-1 month', 'now')->format("d.m.Y в H:i") }}</small></p>
                        <a href="#" class="btn btn-success">Завершить</a>
                        @break
                    @case('success')
                        <p class="card-text"><small class="text-muted">Завершена {{ fake()->dateTimeBetween('-1 month', 'now')->format("d.m.Y в H:i") }}</small></p>
                        @break
                    @case('light')
                        <p class="card-text"><small class="text-muted">Помещена в архив {{ fake()->dateTimeBetween('-1 month', 'now')->format("d.m.Y в H:i") }}</small></p>
                        @break
                @endswitch
            </div>
        </div>
    @endforeach
@endsection



