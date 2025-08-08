@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <h1>{{$h1}}</h1>
    <p>{{$text}}</p>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/content/news.css') }}">
@endsection

@section('script')
    <link rel="stylesheet" href="{{ asset('js/content/news.js') }}">  
@endsection