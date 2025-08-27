@extends(config('iss.layout'))

@push('mainStyles')
    @vite(['Modules/ISS/public/css/adminInterface/issUserCreateOrEdit.css'])
    @vite(['Modules/ISS/public/css/adminInterface/issSharedStyle.css'])
@endpush

@push('mainScripts')
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')


@section('content')

    @if($action === config('iss.ISS_USER_ACTION.edit'))
        <h1>{{__('iss::issAdminUserCRUDInterface.userEditFormLabel')}}</h1>
    @else
        <h1>{{__('iss::issAdminUserCRUDInterface.userCreateFormLabel')}}</h1>
    @endif


    @include('iss::adminInterface.issSharedMessage')


    <div id="userData">
        <form
            enctype="multipart/form-data"
            action="
            @if($action === config('iss.ISS_USER_ACTION.edit'))
                  {{route('MainIssUserManage.update', ['MainIssUserManage' => $userParameters['issUserId']])}}
            @elseif($action === config('iss.ISS_USER_ACTION.create'))
                  {{route('MainIssUserManage.store')}}
            @endif"
        method="POST">

            @csrf
            @if($action === config('iss.ISS_USER_ACTION.edit'))
                @method('put')
            @elseif($action === config('iss.ISS_USER_ACTION.create'))
                @method('post')
            @endif

            <div id="dataTable">
                <table class="table table-success table-striped table-bordered border-light caption-top">
                    <tbody>
                        @foreach($userParameters as $key => $value)
                            <tr>
                                <td>
                                    {{$labels[$key]}}
                                </td>
                                <td>
                                    <input
                                        @if($key === 'issUserId') disabled @endif
                                        id="{{$key}}"
                                        name="{{$key}}"
                                        @if($key === 'avatar') type="file" @else type="text" @endif
                                        @if($key !== 'avatar')
                                            value="{{old($key) ?? $value}}"
                                            placeholder="{{$labels[$key]}}"
                                        @endif
                                    />
                                    <div class="errorMsg">
                                        @error($key) {{__($message)}}  @enderror
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <input type="submit" value="@if($action === config('iss.ISS_USER_ACTION.edit')){{__('iss::issAdminUserCRUDInterface.userEdit')}}@elseif($action === config('iss.ISS_USER_ACTION.create')){{__('iss::issAdminUserCRUDInterface.userCreate')}}@endif" />
        </form>

        <div id="refBack"><a href="{{route('MainIssUserManage.index')}}">{{__('iss::issAdminUserCRUDInterface.refToIssUserList')}}</a></div>
    </div>
@endsection('content')
