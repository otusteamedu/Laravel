<h2>
    @switch($userRole)
        @case('employee') {{__('iss::issUserPage.employeeSection')}} @break
        @case('manager') {{__('iss::issUserPage.managerSection')}} @break
    @endswitch
</h2>
<div id="userData">
    <div id="userAvatar">
        <div>
            <img src="{{asset('images/iss/defaultUserAvatar.png')}}" alt="{{__('iss::issUserPage.altAvatar')}}" />
        </div>
    </div>
    <div id="dataTable">
        @isset($userParameters)
            <table class="table table-success table-striped table-bordered border-light caption-top">
                <caption>
                    {{__('iss::issUserPage.tableCaption')}}
                </caption>
                <tbody>
                @foreach($userParameters as $key => $val)
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$val}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>{{__('iss::issUserPage.noData')}}</p>
        @endisset
    </div>
</div>
<div id="educationRoutes">
    <h2>{{__('iss::issUserPage.educationRoutes')}}</h2>
    @isset($educationChains)
        @foreach($educationChains as $chain)
            <div>
                <div class="routeChain">
                    @isset($chain['nodes'])
                        @foreach($chain['nodes'] as $nodeKey => $nodeValue)
                            <a href="{{route('issEducationChainNode', ['chainId' => $chain['chainId'], 'nodeId' => $nodeKey])}}">
                                <div class="cover @isset($nodeValue['pass']){{$nodeValue['pass']}}@else wait @endisset">
                                    {{$nodeKey}}
                                    <div class="tooltip hide">
                                        @isset($nodeValue['examDate'])
                                            {{__('iss::issUserPage.examDate')}}{{$nodeValue['examDate']}}
                                        @endisset
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endisset
                </div>
                <div class="routeName">
                    @isset($chain['routeName']){{$chain['routeName']}}@else{{__('iss::issUserPage.defaultRouteName')}}@endisset:
                </div>
                <div class="readyPercent">
                    @isset($chain['readyPercent']){{$chain['readyPercent']}}%@else 0% @endisset
                </div>
            </div>
        @endforeach
    @endisset
</div>
