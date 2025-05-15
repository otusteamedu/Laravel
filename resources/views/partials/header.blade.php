<nav class="navbar navbar-expand-lg bg-body-tertiary rounded-2">
    <div class="container-fluid ps-3">
        <a class="main-logo navbar-brand" href="/" title="{{ __("main.home") }}">
            <x-application-logo class="w-14 h-14 fill-current text-gray-500" />
            <span>LISTO</span>
        </a>
        <div class="float-right">
            @include("partials.menu")
        </div>
    </div>
</nav>
