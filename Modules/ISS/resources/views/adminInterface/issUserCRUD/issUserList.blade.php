@extends(config('iss.layout'))

@push('mainStyles')
    @vite(['Modules/ISS/public/css/adminInterface/issUserListStyle.css'])
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
        <caption>{{__('iss::issAdminUserCRUDInterface.issUserList')}}</caption>
        <thead>
        <tr>
            @foreach($labels as $label)
                <th>
                    {{$label}}
                </th>
            @endforeach
                <th>
                    {{__('iss::issAdminUserCRUDInterface.userAction')}}
                </th>
        </tr>
        </thead>
        <tbody>
        @foreach($userParameters as $currentUser)
            <tr>
                @foreach($currentUser as $key => $value)
                    @if($key === 'avatar')
                        <td>
                            <img src="{{asset($value)}}" alt="{{__('iss::issShared.altAvatar')}}" />
                        </td>
                    @else
                        <td>
                            {{$value}}
                        </td>
                    @endif
                @endforeach
                <td>
                <input
                       onclick = "(function() {
                           window.location.href = '{{route('MainIssUserManage.edit', ['MainIssUserManage' => $currentUser['issUserId']])}}'; })();"
                       type="button" value="{{__('iss::issAdminUserCRUDInterface.userEdit')}}" />
                <form style="display: inline;"
                      method="POST" action="{{route('MainIssUserManage.destroy', ['MainIssUserManage' => $currentUser['issUserId']])}}">
                    @csrf
                    @method('delete')
                    <input type="submit" value="{{__('iss::issAdminUserCRUDInterface.userDelete')}}" />
                </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

<div id="refBack"><a href="{{route('issAdmin')}}">{{__('iss::issAdminUserCRUDInterface.refBackToAdminPage')}}</a></div>

@endsection('content')
