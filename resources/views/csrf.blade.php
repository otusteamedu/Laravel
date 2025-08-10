<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <style>
            form {
                background: rosybrown;
                padding: 100px;
                font-size: 20px;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            input, button {
                font-size: 20px;
                padding: 10px;
            }
        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div style="display: flex; flex-direction: row; width: 100%; justify-content: center; margin-top: 100px">
            @php
                /**
                * @see \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
                * @see \Illuminate\Foundation\Configuration\Middleware:446
                */
            @endphp

            <form action="/send-money" method="post">
                <input type="tel" name="phone" value="" placeholder="Phone">
                <input type="number" name="amount" value="" placeholder="Сумма">
                <button type="submit">Отправить денюжку</button>
            </form>

        </div>
    </body>
</html>
