<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        .odd {
            color: red
        }

        .even {
            color: blueviolet
        }

        .first {
            font-size: 2rem;
        }
    </style>
</head>

@php($version = phpversion())

<body>
    <h1>Hello from PHP. {{ 8.0 + 0.4 }} {{  $version }}</h1>

    <ol>
        @foreach($names as $name)
            <li @class([
                'odd' => $loop->odd,
                'even' => $loop->even,
                'first' => $loop->first
            ])>{{ $name }}</li>
        @endforeach
    </ol>
    {{  $version }}
</body>

</html>
