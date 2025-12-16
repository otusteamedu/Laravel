<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name')}} - ASCII Render</title>
</head>

<body>
    <h1>Upload your image</h1>
    <form action="{{ URL::route(config('ascii.route_name_prefix') . 'render') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <input type="file" name="image">
        <input type="submit" name="type" value="text">
        <input type="submit" name="type" value="colored">
    </form>
</body>

</html>