@php
/**
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email" class="form-label">{{ __('Email') }}</label>
    <div class="mb-3 input-group">
        <input id="email" name="email" type="email" 
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('email'))
            ]) 
            placeholder="name@example.com" autofocus required autocomplete="username" />
        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
        <x-todo-app::invalid-feedback :errors="$errors->get('email')"/>
    </div>

    <label for="password" class="form-label">{{ __('Password') }}</label>
    <div class="mb-3 input-group">
        <input id="password" name="password" type="password" 
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('password'))
            ]) 
            placeholder="{{ __('Enter you password') }}" required autocomplete="current-password">
        <span class="input-group-text password-toggle"><i class="fa-solid fa-eye"></i></span>
        <x-todo-app::invalid-feedback :errors="$errors->get('password')"/>
    </div>

    <div class="form-check d-flex justify-content-between">
        <div>
            <input name="remember" type="checkbox" class="form-check-input my-2" id="remember">
            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">{{ __('Log in') }}</button>
</form>
