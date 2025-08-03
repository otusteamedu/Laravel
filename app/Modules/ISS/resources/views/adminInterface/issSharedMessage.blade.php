@error('actionError')
<div id="actionError">
    {{__($message)}}
</div>
@enderror

@if($success)
    <div id="actionSuccess">
        {{__($success)}}
    </div>
@endif
