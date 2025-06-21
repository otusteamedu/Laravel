@env('local')
    <!--
    Общие стили для всех страниц сайта
-->
@endenv('local')


<link type="text/css" href="{{asset('/js/bootstrap-5.3.3-dist/css/bootstrap.min.css')}}" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="{{asset('/js/plugins/jquery-ui-1.14.1.base/jquery-ui.css')}}">

<!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/mainStyle.css')--}}"> -->
@vite(['resources/css/mainStyle.css'])





