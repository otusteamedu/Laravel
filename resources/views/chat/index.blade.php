@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <div class="p-3 bg-body-tertiary rounded-3">
            <h1 class="display-6 fw-bold text-center">Чат</h1>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                @guest
                <div>
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        Авторизуйтесь, чтобы написать сообщение
                    </a>
                </div>
                @endguest

                @auth
                <form action="{{ route('chat.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control" rows="2" placeholder="Текст сообщения" name="content"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                </form>
                @endauth

                <div id="messages" class="mt-4">
                    @foreach ($messages as $message)
                        <div>
                            <div class="fw-bold">
                                {{ $message->getUser()->name }}, {{ $message->getCreatedAt()->format('d.m.Y H:i') }}
                            </div>
                            <div class="mb-4">{{ $message->getContent() }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection