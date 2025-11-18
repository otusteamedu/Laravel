<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    @dump(Route::current())
    <pre>{{ route('csrf', [], false) }}</pre>
    <pre>{{ route('qwe.calc', ['a' => 1, 'b' => 3], false) }}</pre>
</body>

</html>