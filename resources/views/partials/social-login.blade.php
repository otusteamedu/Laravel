<div class="social-login d-flex flex-column justify-content-center gap-2">
    @if(config('services.yandex.client_id'))
        <a href="{{ route('login.yandex') }}" class="btn btn-yandex">
            <i class="fab fa-yandex pe-2"></i>Яндекс
        </a>
    @endif
    @if(config('services.vkid.client_id'))
         <a href="{{ route('login.vk') }}" class="btn btn-vkontakte">
            <i class="fab fa-vk pe-2"></i>ВКонтакте
         </a>
    @endif
</div>