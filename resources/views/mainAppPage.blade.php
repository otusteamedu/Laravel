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

    <div id="refToISS">
        <h3><a class="link-underline-info" href="{{route('iss')}}">{{__('mainAppPage.refToISS')}}</a></h3>
    </div>
@endsection('content')
