<!DOCTYPE HTML>
<html lang="ru">

@env('local')
    <!--Главный layout шаблон приложения. От него наследуются все остальные шаблоны

        Обязательные переменные:
        //
	-->
@endenv('local')

<head>
    <title>
        {{__('Обучение ОТУС тестовое приложение')}}@isset($moduleName)::{{$moduleName}}@endisset</title>
    <link rel="shortcut icon" href="{{asset('/images/myApp.ico')}}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta charset="utf-8">
    <meta name="language" content="{{session('userLocale', config('app.locale'))}}">


    @include('layouts.mainStyles')
    @stack('mainStyles')
</head>
<body>
    <div id="container">
        <div id="mainMenu">
            @section('mainMenu')<h3>Error: menu damaged</h3>@show('mainMenu')
        </div>

        <div id="content">
            @section('content')<h3>Error: no content</h3>@show('content')
        </div>
    </div>

    @include('layouts.mainScripts')
    @stack('mainScripts')
</body>
</html>
