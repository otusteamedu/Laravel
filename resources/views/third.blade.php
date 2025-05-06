<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        .odd {
            color: blue
        }

        .even {
            color: red
        }
    </style>
</head>

@php
    $title = 'Title'
@endphp

<body>
    <h1> {{  $title }}</h1>
    <h1>Hello from Blade {{ 8 + 0.3 }} {{ phpversion() }}</h1>
    @if ($show)
        <h2>Hello {!! $name !!}</h2>
    @endif

    <ol>
        @foreach($users as $user)
            @break($user === "Mike")
            <li @class(["odd" => $loop->odd, 'even' => $loop->even])>{{ $user }}</li>
        @endforeach
    </ol>
</body>

</html>
