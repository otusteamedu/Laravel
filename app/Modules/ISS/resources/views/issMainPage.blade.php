@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issMainPageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issSharedStyle.css'])
    @vite(['app/Modules/ISS/public/css/issMainPageStyle.css'])
@endpush

@push('mainScripts')
    <script src="{{asset('js/iss/issMainPage.js')}}"></script>
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

    <div id="issMainBlock">
        @issGuest <!-- Если не зарегистрирован в ИОС -->
        <div id="authAlert">
            <h2>{{__('iss::issMainPage.alertNeedAuthorization')}}</h2>
        </div>
        @endissGuest

        @issAuth <!-- для авторизованных пользователей ИОС -->
            <h4><a href="{{route('issUser', ['issUserId' => $issUser->issUserId])}}">{{__('iss::issMainPage.refTuISSUserPage')}}</a></h4>
            <h4><a href="{{route('issAdmin')}}">{{__('iss::issMainPage.refTuISSAdminPage')}}</a></h4>
        @endissAuth

        <div id="refToMainISSPage">
            @include('iss::blocks.refToMainApp')
        </div>
    </div>
    <div id="message">@isset($msg){{$msg}}@endisset</div>
@endsection('content')
