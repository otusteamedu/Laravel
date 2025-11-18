<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Edit post</h1>
    <form action="{{ route('posts.store') }}" method="post">
        @csrf
        <input type="text" name="title">
        <button type="submit">Отправить</button>
    </form>
</body>

</html>
