@php
/**
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email" class="form-label">Email адрес</label>
    <div class="mb-3 input-group">
        <input id="email" name="email" type="email" 
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('email'))
            ]) 
            placeholder="name@example.com" autofocus required autocomplete="username" />
        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
        <x-invalid-feedback :errors="$errors->get('email')"/>
    </div>

    <label for="password" class="form-label">Пароль</label>
    <div class="mb-3 input-group">
        <input id="password" name="password" type="password" 
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('password'))
            ]) 
            placeholder="Введите ваш пароль" required autocomplete="current-password">
        <span class="input-group-text password-toggle"><i class="fa-solid fa-eye"></i></span>
        <x-invalid-feedback :errors="$errors->get('password')"/>
    </div>

    <div class="form-check d-flex justify-content-between">
        <div>
            <input name="remember" type="checkbox" class="form-check-input my-2" id="remember">
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Войти</button>
</form>
