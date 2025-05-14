@php
/**
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<div class="p-4 tab-pane col-md-6 fade" id="security" role="tabpanel" aria-labelledby="security-tab">
    <div class="mb-4">
        <h4 class="mb-4">Смена пароля</h4>
        <form method="POST" action="{{ route('password.update') }}#security-tab">
            @csrf
            @method('put')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Текущий пароль</label>
                    <div class="mb-3 input-group">
                        <input name="current_password" type="password"
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->updatePassword->get('current_password'))
                            ]) 
                            placeholder="Укажите текущий пароль" required autocomplete="current-password">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <x-invalid-feedback :errors="$errors->updatePassword->get('current_password')"/>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Новый пароль</label>
                    <div class="mb-3 input-group">
                        <input name="password" type="password" 
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->updatePassword->get('password'))
                            ]) 
                            placeholder="Введите пароль, который нужно установить" required autocomplete="new-password">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <x-invalid-feedback :errors="$errors->updatePassword->get('password')"/>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Подтвердите новый пароль</label>
                    <div class="mb-3 input-group">
                        <input name="password_confirmation" type="password" 
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->updatePassword->get('password_confirmation'))
                            ]) 
                            placeholder="Введите пароль, который нужно установить" required autocomplete="new-password">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <x-invalid-feedback :errors="$errors->updatePassword->get('password_confirmation')"/>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Обновить пароль</button>
                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-success p-2"
                        >Пароль обновлен</p>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
