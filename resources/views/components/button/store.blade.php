@php
/**
* @var string $route
* @var string $name
* @var string $fields
* @var string $redirect_route
*/
@endphp

<button type="button" 
        class="btn btn-primary store-{{ $name }}-btn" 
        data-route="{{ $route }}" 
        data-fields="{{ $fields }}" 
        data-redirect-route="{{ $redirect_route }}">
    {{ __('account.store_btn') }}
</button>