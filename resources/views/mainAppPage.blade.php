@extends('layouts.mainViewTemplate')

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/mainAppPageStyle.css')--}}"> -->
    @vite(['resources/css/mainAppPageStyle.css'])
@endpush


@section('mainMenu')
    ___________________________________________
    <!-- В этом тестовом проекте меню не будет использоваться, но в рабочем будет, поэтому оставлю заготовку здесь -->
@endsection('mainMenu')


@section('content')
    <div id="welcome">
        <h2>{{__('mainAppPage.welcome')}}</h2>
        <div id="description">
            <p class="font-monospace">{{__('mainAppPage.description')}}</p>
        </div>
    </div>

    <div class="decorPicture">
        <img src="{{asset('/images/mainPageDecor.jpg')}}" alt="{{__('mainAppPage.decorImage')}}" />
    </div>

    @guest
    <div class="refOnMainPage">
        <h3><a class="link-underline-info" href="{{route('register')}}">{{__('mainAppPage.refToMainAppRegister')}}</a></h3>
    </div>
    <div class="refOnMainPage">
        <h3><a class="link-underline-info" href="{{route('login')}}">{{__('mainAppPage.refToMainAppLogin')}}</a></h3>
    </div>
    @endguest

    @auth
    <div class="refOnMainPage">
        <h3><a class="link-underline-info" href="{{route('dashboard')}}">{{__('mainAppPage.refToMainAppDashboard')}}</a></h3>
    </div>
    <div class="refOnMainPage">
        <h3><a class="link-underline-info" href="{{route('profile.edit')}}">{{__('mainAppPage.refToMainAppUserProfile')}}</a></h3>
    </div>
    <div class="refOnMainPage">
        <h3><a class="link-underline-info" href="{{route('iss')}}">{{__('mainAppPage.refToISS')}}</a></h3>
    </div>

    <div class="refOnMainPage"> <!-- ПРИМЕР ТОЛЬКО ДЛЯ ДЗ №6 !!! -->
        <h3>
            <a class="link-underline-info" href="{{route('editUserOfMainUp', ['userForEditId' => $mainAppUserId])}}">
                {{__('mainAppPage.ExampleHW6')}}
            </a>
        </h3>
    </div>
    @endauth
@endsection('content')
