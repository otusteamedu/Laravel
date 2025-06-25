@extends(config('package.layout'))

@push('mainStyles')
    <link type="text/css" rel="stylesheet" href="{{asset('/css/package/package.css')}}">
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')


@section('content')
    <div id="test"><p>PACKAGE!!!!</p></div>
    @empty($packages)
        <h3>{{__('packageWithPackages::packageLang.noPackagesFound')}}</h3>
    @else
        <table class="table table-success table-striped table-bordered border-light caption-top">
            <caption>{{__('packageWithPackages::packageLang.tableHead')}}</caption>
                <tr>
                    <th>
                        {{__('packageWithPackages::packageLang.tHeaderPackageName')}}
                    </th>
                    <th>
                        {{__('packageWithPackages::packageLang.tHeaderPackageFill')}}
                    </th>
                </tr>
            <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>
                        {{$package->packageName}}
                    </td>
                    <td>
                        {{$package->packageContent}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endempty
@endsection
