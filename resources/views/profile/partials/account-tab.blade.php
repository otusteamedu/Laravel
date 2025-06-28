@php
/**
 * @var \App\Models\User $user
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<div class="p-4 tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
    <div class="mb-4">
        <h4 class="mb-4">Персональная информация</h4>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Имя</label>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input name="name" type="text"
                            value="{{ old('name', $user->name) }}"
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->get('name'))
                            ]) 
                            placeholder="Имя" required autofocus autocomplete="name" >
                        <x-invalid-feedback :errors="$errors->get('name')"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input name="email" type="email"
                            value="{{ old('email', $user->email) }}"
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->get('email'))
                            ]) 
                            placeholder="Email адрес" required autocomplete="username">
                        <x-invalid-feedback :errors="$errors->get('email')"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telegram ID</label>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fa-brands fa-telegram"></i></span>
                        <input name="profile[telegram_id]" type="number"
                            value="{{ old('profile[telegram_id]', $user->profile->telegram_id) }}"
                            @class([
                                'form-control',
                                'is-invalid' => !empty($errors->get('profile.telegram_id'))
                            ]) 
                            placeholder="Telegram ID" autocomplete="off">
                        <x-invalid-feedback :errors="$errors->get('profile.telegram_id')"/>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">О себе</label>
                    <textarea name="profile[biography]"class="form-control" rows="4">{{ $user->profile->biography }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>
