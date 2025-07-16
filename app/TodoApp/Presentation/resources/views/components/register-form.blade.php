@php
/**
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fa fa-user"></i></span>
        <input name="name" type="text" 
            value="{{ old('name') }}"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('name'))
            ]) 
            placeholder="{{ __('Name') }}" required autofocus autocomplete="name" >
        <x-todo-app::invalid-feedback :errors="$errors->get('name')"/>
    </div>
    <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
        <input name="email" type="email" 
            value="{{ old('email') }}"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('email'))
            ]) 
            placeholder="Email адрес" required autocomplete="username">
        <x-todo-app::invalid-feedback :errors="$errors->get('email')"/>
    </div>
    <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fa fa-lock"></i></span>
        <input name="password" type="password" 
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('password'))
        ]) 
        placeholder="{{ __('Password') }}" required autocomplete="new-password">
        <x-todo-app::invalid-feedback :errors="$errors->get('password')"/>
    </div>
    <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fa fa-lock"></i></span>
        <input name="password_confirmation" type="password" 
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('password_confirmation'))
        ]) 
        placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
        <x-todo-app::invalid-feedback :errors="$errors->get('password_confirmation')"/>
    </div>                                      
    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>
    </div>
</form>
