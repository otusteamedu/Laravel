@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <h1>{{$name}}</h1>
    <h2>Дата рождения: {{ $date }}</h2>
    <h2>Группа: {{ $group }}</h2>
    <p>{{$text}}</p>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/show.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/user/show.js') }}">  
@endsection