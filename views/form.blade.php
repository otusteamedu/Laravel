<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascii form</title>
</head>

<body>
    <h1>Image to Ascii converter</h1>
    <form action="{{ route('ascii.render') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="file">
        <input type="submit" name="type" value="to ASCII" />
        <input type="submit" name="type" value="to colored ASCII" />
    </form>
</body>

</html>