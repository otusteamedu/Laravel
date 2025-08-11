@extends('layout')

@php
    /**
     * @var \App\Models\User $user
     */
@endphp


@section('title', $user->name)

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold my-4">
            {{ $user->name }}
        </h1>

        <p>
            Email: {{ $user->email }}
        </p>
    </div>
@endsection
