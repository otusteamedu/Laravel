<div id="diagrams">
    <h3>{{__('iss::issUserPage.diagramHeader')}}</h3>
    <div id="diagramCollection">
    @isset($diagrams)
        @if((count($diagrams)  == 1 && $userRole == 'manager') || $userRole == 'admin')
            @foreach($diagrams as $organization => $value)
                @if(!is_numeric($organization))
                <div class="graphic">
                    <h3>{{$organization}}</h3>
                    <div>
                        <canvas id="myChart_{{$organization}}"></canvas>
                    </div>

                    <div class="initialData hide">
                        <div class="json">{{$value['json']}}</div>
                        <div class="diagramName">{{__('iss::issUserPage.baseDiagramName', ['firm' => $value['diagramName']])}}</div>
                    </div>
                </div>
                @endif
            @endforeach
        @else
                <p>{{__('iss::issUserPage.noDataToLoad')}}</p>
        @endif
    @endisset
    </div>
</div>
