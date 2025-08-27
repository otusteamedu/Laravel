@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issUserPageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['Modules/ISS/public/css/issSharedStyle.css'])
    @vite(['Modules/ISS/public/css/issUserPageStyle.css'])

    <link type="text/css" href="{{asset('js/plugins/jQuerySmartMenu/dist/css/sm-core-css.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('js/plugins/jQuerySmartMenu/dist/css/sm-clean/sm-clean.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('js/plugins/jQuerySmartMenu/dist/css/sm-simple/sm-simple.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('js/plugins/jQuerySmartMenu/dist/css/sm-blue/sm-blue.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('js/plugins/jQuerySmartMenu/dist/css/sm-mint/sm-mint.css')}}" rel="stylesheet">
@endpush

@push('mainScripts')
    <script src="{{asset('js/iss/Chartjs4-4-9.js')}}"></script>

    <script src="{{asset('js/plugins/jQuerySmartMenu/dist/jquery.smartmenus.js')}}"></script>
    <script src="{{asset('js/plugins/jQuerySmartMenu/dist/addons/bootstrap-4/jquery.smartmenus.bootstrap-4.min.js')}}"></script>
    <script src="{{asset('js/plugins/jQuerySmartMenu/dist/addons/bootstrap/jquery.smartmenus.bootstrap.min.js')}}"></script>
    <script src="{{asset('js/plugins/jQuerySmartMenu/dist/addons/keyboard/jquery.smartmenus.keyboard.min.js')}}"></script>

    <script src="{{asset('js/iss/issUserPage.js')}}"></script>
    <script src="{{asset('js/iss/issAdminPage.js')}}"></script>
@endpush

@section('mainMenu')
    ___________________________________________
    <ul id="main-menu" class="sm sm-mint">
        <li><a href="">{{__('iss::issAdminPage.issUserMenu')}}</a>
            <ul>
                <li><a href="{{route('MainIssUserManage.index')}}">{{__('iss::issAdminPage.listIssUsers')}}</a></li>
                <li><a href="{{route('MainIssUserManage.create')}}">{{__('iss::issAdminPage.createIssUser')}}</a></li>
            </ul>
        </li>
        <li><a href="">{{__('iss::issAdminPage.issRefEducationPointMenu')}}</a>
            <ul>
                <li><a href="{{route('RoutePointManage.index')}}">{{__('iss::issAdminPage.listRefEducationPoints')}}</a></li>
                <li><a href="{{route('RoutePointManage.create')}}">{{__('iss::issAdminPage.createReferenceEducationPoint')}}</a></li>
            </ul>
        </li>
        <li><a href="">{{__('iss::issAdminPage.issRefEducationRoutesMenu')}}</a>
            <ul>
                <li><a href="">{{__('iss::issAdminPage.listRefEducationRoutes')}}</a></li>
                <li><a href="">{{__('iss::issAdminPage.createRefEducationRoute')}}</a></li>
            </ul>
        </li>
        <li><a href="">{{__('iss::issAdminPage.issEducationRoutesOfUsersMenu')}}</a>
            <ul>
                <li><a href="">{{__('iss::issAdminPage.listRoutesOfUsers')}}</a></li>
            </ul>
        </li>
        <li><a href="">{{__('')}}</a></li>
    </ul>
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
