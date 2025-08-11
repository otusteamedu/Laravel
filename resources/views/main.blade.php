@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <h1>{{$h1}}</h1>
    <p>{{ $text }}</p>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">    
@endsection
@section('script')
    <link rel="stylesheet" href="{{ asset('js/main.js') }}">  
@endsection
