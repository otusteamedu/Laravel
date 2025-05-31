@extends ('layouts.root')

@section ('body')
@php
/**
* @var array<array-key,array{name: string, age:integer, city: string}>> $users
*/
@endphp
    <div class="container">
        <h2>Нажав на имя нужного пользователя, Вы можете получить более подробную информацию о нем.</h2>
        <div class="user-list">
            @foreach ($users as $user)
                <a href='?name={{ $user["name"] }}'>{{ $user["name"] }}</a>
            @endforeach
        </div>

        @foreach ($users as $user)
            @if ($user["name"] === $name)
                <p>Имя: {{ $user["name"] }} </p>
                <p>Возраст: {{ $user["age"] }} </p>
                <p>Город: {{ $user["city"] }} </p>
            @endif
        @endforeach

    </div>
@endsection
