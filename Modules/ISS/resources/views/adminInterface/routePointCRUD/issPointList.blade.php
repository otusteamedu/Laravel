@extends(config('iss.layout'))

@push('mainStyles')
    @vite(['Modules/ISS/public/css/adminInterface/issPointListStyle.css'])
    @vite(['Modules/ISS/public/css/adminInterface/issSharedStyle.css'])
@endpush

@push('mainScripts')
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')

@section('content')

@include('iss::adminInterface.issSharedMessage')

    <table class="table table-success table-striped table-bordered border-light caption-top">
        <caption>{{__('iss::issAdminPointCRUDInterface.issPointList')}}</caption>
        <thead>
        <tr>
            @foreach($labels as $label)
                <th>
                    {{$label}}
                </th>
            @endforeach
                <th>
                    {{__('iss::issAdminPointCRUDInterface.pointAction')}}
                </th>
        </tr>
        </thead>
        <tbody>
        @foreach($pointParameters as $currentPoint)
            <tr>
                @foreach($currentPoint as $key => $value)
                    <td>
                        {{$value}}
                    </td>
                @endforeach
                <td>
                <input
                       onclick = "(function() {
                           window.location.href = '{{route('RoutePointManage.edit', ['RoutePointManage' => $currentPoint['pointId']])}}'; })();"
                       type="button" value="{{__('iss::issAdminPointCRUDInterface.pointEdit')}}" />
                <form style="display: inline;"
                      method="POST" action="{{route('RoutePointManage.destroy', ['RoutePointManage' => $currentPoint['pointId']])}}">
                    @csrf
                    @method('delete')
                    <input type="submit" value="{{__('iss::issAdminPointCRUDInterface.pointDelete')}}" />
                </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

<div id="refBack"><a href="{{route('issAdmin')}}">{{__('iss::issAdminPointCRUDInterface.refBackToAdminPage')}}</a></div>

@endsection('content')
