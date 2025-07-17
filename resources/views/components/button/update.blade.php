@php
/**
* @var string $route
* @var string $name
* @var string $fields
* @var string $redirect_route
*/
@endphp

<button type="button" 
        class="btn btn-primary update-{{ $name }}-btn" 
        data-route="{{ $route }}" 
        data-fields="{{ $fields }}" 
        data-redirect-route="{{ $redirect_route }}">
    {{ __('account.update_btn') }}
</button>