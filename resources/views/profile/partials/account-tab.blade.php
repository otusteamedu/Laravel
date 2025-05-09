<div class="p-4 tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
    <div class="mb-4">
        <h4 class="mb-4">Персональная информация</h4>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            @if (session('status') === 'profile-updated')
            @endif

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
                <div class="col-12">
                    <label class="form-label">О себе</label>
                    <textarea name="biography"class="form-control" rows="4">TODO</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-success p-2"
                        >Информация обновлена</p>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
