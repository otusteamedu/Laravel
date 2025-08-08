@extends('layouts.app')

@section('title', 'Авторизация в личном кабинете')

@section('content')
    <h1>{{$name}}</h1>
    <h2>Дата рождения: {{ $date }}</h2>
    <h2>Группа: {{ $group }}</h2>
    <p>{{$text}}</p>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/auth.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/user/auth.js') }}">  
@endsection