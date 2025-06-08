@php
/**
* @var string $route
* @var string $name
*/
@endphp

<button type="button" 
        class="btn-edit edit-{{ $name }}-btn"
        data-route="{{ $route }}">
    {{ __('account.edit_btn') }}
</button>
