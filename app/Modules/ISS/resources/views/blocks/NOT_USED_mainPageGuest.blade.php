@issGuest
<div id="loginForm">
    <form action="" method="POST"> <!-- КОГДА СДЕЛАЮ КОНТРОЛЛЕР ДОБАВИТЬ МАРШРУТ -->
        @csrf
        <div class="mb-3">
            <label class="form-label myFormCorrectionLabel" for="idLogin">{{__('iss::issMainPage.loginLabel')}}</label>
            <input type="text" id="idLogin" class="form-control myFormCorrectionInput"
                   name="login" placeholder="{{__('iss::issMainPage.loginPlaceholder')}}"
                   value="{{old('login')}}" />
            <div class="errorMsg">
                @error('login') {{__($message)}}  @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label myFormCorrectionLabel" for="idLogin">{{__('iss::issMainPage.passwordLabel')}}</label>
            <input type="text" id="idPassword" class="form-control myFormCorrectionInput"
                   name="password" placeholder="{{__('iss::issMainPage.passwordPlaceholder')}}"
                   value="{{old('password')}}" />
            <div class="errorMsg">
                @error('password') {{__($message)}}  @enderror
            </div>
        </div>
        <div class="formButtonWrap">
            <input type="reset" class="btn btn-primary myFormCorrectionButtons" value="{{__('iss::issMainPage.reset')}}"/>
            <input type="submit" class="btn btn-primary myFormCorrectionButtons" value="{{__('iss::issMainPage.submit')}}"/>
        </div>
    </form>
</div>
@endissGuest
