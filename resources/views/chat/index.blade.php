@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <div class="p-3 bg-body-tertiary rounded-3">
            <h1 class="display-6 fw-bold text-center">Чат</h1>
        </div>

        <div>
            @foreach ($messages as $message)
            <div>
                <p>{{ $message->getContent() }}</p>
                <p>{{ $message->getUser()->name }}</p>
                <p>{{ $message->getCreatedAt()->format('d.m.Y H:i') }}</p>
            </div>
            @endforeach
        </div>
    </div>
@endsection