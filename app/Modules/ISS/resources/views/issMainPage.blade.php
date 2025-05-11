@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issMainPageStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issMainPageStyle.css'])
@endpush

@section('mainMenu')
    ___________________________________________
    <!-- В этом тестовом проекте меню не будет использоваться, но в рабочем будет, поэтому оставлю заготовку здесь -->
@endsection('mainMenu')


@section('content')
    <div id="welcomeISS">
        <h2>{{__('iss::issMainPage.issWelcome')}}</h2>
        <p>{{__('iss::issMainPage.description')}}</p>
    </div>
    <div id="loginForm">
        <form action="" method="POST"> <!-- КОГДА СДЕЛАЮ КОНТРОЛЛЕР ДОБАВИТЬ МАРШРУТ -->
            @csrf
            <div class="mb-3">
                <label class="form-label myFormCorrectionLabel" for="idLogin">{{__('iss::issMainPage.loginLabel')}}</label>
                <input type="text" id="idLogin" class="form-control myFormCorrectionInput"
                       name="login" placeholder="{{__('iss::issMainPage.loginPlaceholder')}}"
                       value="{{old('login')}}" />
                <div class="errorMsg">
                    @error('login') {{__($message)}}  @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label myFormCorrectionLabel" for="idLogin">{{__('iss::issMainPage.passwordLabel')}}</label>
                <input type="text" id="idPassword" class="form-control myFormCorrectionInput"
                       name="password" placeholder="{{__('iss::issMainPage.passwordPlaceholder')}}"
                       value="{{old('password')}}" />
                <div class="errorMsg">
                    @error('password') {{__($message)}}  @enderror
                </div>
            </div>
            <div class="formButtonWrap">
                <input type="reset" class="btn btn-primary myFormCorrectionButtons" value="{{__('iss::issMainPage.reset')}}"/>
                <input type="submit" class="btn btn-primary myFormCorrectionButtons" value="{{__('iss::issMainPage.submit')}}"/>
            </div>
        </form>
    </div>
    <div id="message"></div>

    </br><p><a href="{{route('main')}}">{{__('iss::issMainPage.refToMain')}}</a></p>
    </br><p><a href="{{route('issUser', ['id' => 12345])}}">ВРЕМЕННАЯ ССЫЛКА НА СТР ПОЛЬЗОВАТЕЛЯ (пока нет контроллера авторизации)</a></p>

@endsection('content')
