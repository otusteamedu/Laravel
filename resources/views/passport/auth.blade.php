<h1>Авторизация для {{ $client->name }}</h1>

<p>Запрашиваемые разрешения:</p>
<ul>
    @foreach ($scopes as $scope)
        <li>{{ $scope->description }}</li>
    @endforeach
</ul>

<form method="POST" action="{{ route('passport.authorizations.approve') }}">
    @csrf
    <input type="hidden" name="state" value="{{ $state }}">
    <input type="hidden" name="client_id" value="{{ $client_id }}">
    <input type="hidden" name="auth_token" value="{{ $authToken }}">

    <button type="submit">Разрешить доступ</button>
</form>

<form method="POST" action="{{ route('passport.authorizations.deny') }}">
    @csrf
    <button type="submit">Запретить доступ</button>
</form>
