<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>

<body>
    <h1>Hello from PHP. <?= 8.0 + 0.4 ?> <?= phpversion() ?></h1>

    <?php if (isset($name)): ?>
        <h3>Hello <?= htmlspecialchars($name) ?></h3>
    <?php endif ?>
</body>

</html>
