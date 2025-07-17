@php
/**
* @var string $route
* @var string $name
*/
@endphp

<button type="button" 
        class="btn-delete delete-{{ $name }}-btn"
        data-route="{{ $route }}">
    {{ __('account.delete_btn') }}
</button>
