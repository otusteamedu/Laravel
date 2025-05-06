<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>

<body>
    <h1>Hello from PHP <?= 8 + 0.3 ?> <?= phpversion() ?></h1>
    <?php if ($show): ?>
        <h2>Hello <?= htmlspecialchars($name) ?></h2>
    <?php endif ?>
</body>

</html>
