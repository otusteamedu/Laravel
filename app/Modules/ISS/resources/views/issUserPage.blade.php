@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issUserPageStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issUserPageStyle.css'])
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
        @case('admin')
            <h2>{{__('iss::issUserPage.adminSection')}}</h2>
            @include('iss::blocks.userDiagrams')
            <div id="makeEducationRouteInstrument">
                education routes CONSTRUCTOR (plan to future)
            </div>
        @break
        @case('manager')
            @include('iss::blocks.userMainSection')
            @include('iss::blocks.userDiagrams')
        @break
        @case('employee')
            @include('iss::blocks.userMainSection')
        @break
        @default @break
    @endswitch

    <p><a href="{{route('main')}}">{{__('iss::issMainPage.refToMain')}}</a></p>
@endsection('content')
