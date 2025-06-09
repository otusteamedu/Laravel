@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issUserPageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issSharedStyle.css'])
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
    <h2>{{__('iss::issUserPage.adminSection')}}</h2>
    @include('iss::blocks.userDiagrams')
    <div id="makeEducationRouteInstrument">
        education routes CONSTRUCTOR (plan to future)
    </div>


    <div id="refBack">
        @include('iss::blocks.refToMainISS')
        @include('iss::blocks.refToMainApp')
    </div>
@endsection('content')
