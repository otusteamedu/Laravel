<h2>
    @switch($userRole)
        @case('employee') {{__('iss::issUserPage.employeeSection')}} @break
        @case('manager') {{__('iss::issUserPage.managerSection')}} @break
    @endswitch
</h2>
<div id="userData">
    <div id="userAvatar">
        <div>
            <img src="@isset($userParameters['userAvatar']){{asset('images/iss/'.$userParameters['userAvatar'])}}
            @else{{asset('images/iss/defaultUserAvatar.png')}}@endisset" alt="{{__('iss::issUserPage.altAvatar')}}" />
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
                        @if($key != 'userAvatar')
                        <td>{{$key}}</td>
                        <td>{{$val}}</td>
                        @endif
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
    @isset($routes)
        @foreach($routes as $route)
            <div>
                <div class="routeChain">
                    @isset($route['points'])
                        @foreach($route['points'] as $pointKey => $pointValue)
                            <a href="{{route('issEducationRoutePoint',
                                      [
                                          'routeId' => $route['routeId'],
                                          'pointId' => $pointValue['realRoutePointId'],
                                          'issUserId' => $issUserId
                                      ])}}">
                                <div class="cover @isset($pointValue['pass']){{$pointValue['pass']}}@else wait @endisset">
                                    {{$pointKey}}
                                    <div class="tooltip hide">
                                        @isset($pointValue['routePointName'])
                                            {{__('iss::issUserPage.routePointName')}}{{$pointValue['routePointName']}}
                                        @endisset
                                        </br>
                                        @isset($pointValue['examDate'])
                                            {{__('iss::issUserPage.examDate')}}{{$pointValue['examDate']}}
                                        @endisset
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endisset
                </div>
                <div class="routeName">
                    @isset($route['routeName']){{$route['routeName']}}@else{{__('iss::issUserPage.defaultRouteName')}}@endisset:
                </div>
                <div class="readyPercent">
                    @isset($route['readyPercent']){{$route['readyPercent']}}%@else 0% @endisset
                </div>
            </div>
        @endforeach
    @endisset
</div>
