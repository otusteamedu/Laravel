<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config ('app.name'))</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
        <link href="{{ asset('css/style.css') }}"  rel="stylesheet">

    </head>
    <div class="main">
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-3 menu-item">
                        <a href="/page">Главная</a>
                    </div>
                    <div class="col-3 menu-item">
                        <a href="/reg">Страница регистрации</a>
                    </div>
                    <div class="col-3 menu-item">
                        <a href="/user">Страница пользователя</a>
                    </div>
                    <div class="col-3 menu-item">
                        <a href="/abstr">Абстрактная страница</a>
                    </div>
                </div>
            </div>
        </header>
        <body>
            <div class="container">
                @yield('body')
            </div>
            
        </body>
    </div>
</html>