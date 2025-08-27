@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issUserPageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['Modules/ISS/public/css/issSharedStyle.css'])
    @vite(['Modules/ISS/public/css/issUserPageStyle.css'])
@endpush

@push('mainScripts')
    <script src="{{asset('js/iss/Chartjs4-4-9.js')}}"></script>
    <script src="{{asset('js/iss/issUserPage.js')}}"></script>
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')


@section('content')
    @switch($userRole)
        @case(config('iss.ROLE_MANAGER')) @case(config('iss.ROLE_ADMIN'))
            @include('iss::blocks.userMainSection')
            @include('iss::blocks.userDiagrams')
        @break
        @case('employee')
            @include('iss::blocks.userMainSection')
        @break
        @default @break
    @endswitch

    <div id="refBack">
        @include('iss::blocks.refToMainISS')
        @include('iss::blocks.refToMainApp')
    </div>
@endsection('content')
