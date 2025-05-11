@extends(config('iss.layout'))


@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issNodePageStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issNodePageStyle.css'])
@endpush

@push('mainScripts')
    <script src="{{asset('js/iss/issNodePage.js')}}"></script>
@endpush

@section('mainMenu')
    ___________________________________________
    <!-- В этом тестовом проекте меню не будет использоваться, но в рабочем будет, поэтому оставлю заготовку здесь -->
@endsection('mainMenu')

@section('content')
    <div id="tabPanels">
        <ul>
            <li><a href="#main">{{__('iss::issNodePage.mainTabName')}}</a></li>
            <li><a href="#videoInstructions">{{__('iss::issNodePage.videoInstructionTabName')}}</a></li>
            <li><a href="#textAndPdfInstructions">{{__('iss::issNodePage.instructionTabName')}}</a></li>
            <li><a href="#exam">{{__('iss::issNodePage.examTabName')}}</a></li>
        </ul>

        <div id="main">
            <p>{{__('iss::issNodePage.mainDescription',
                ['routeName' => $nodeData['routeName'], 'nodeName' => $nodeData['nodeName']])}}</p>
            <h2>{{__('iss::issNodePage.examResult',
                ['result' => $nodeData['examResult'], 'examDate' => $nodeData['examDate']])}}</h2>
        </div>
        <div id="videoInstructions">
            <select id="videoSelector">
                <option>{{__('iss::issNodePage.selectVideo')}}</option>
            </select>

            <input type="button" id="loadVideo" value="{{__('iss::issNodePage.loadVideo')}}" />
            <input type="button" id="viewVideo" value="{{__('iss::issNodePage.viewVideo')}}" />
            <div id="videoFrame"></div>
        </div>
        <div id="textAndPdfInstructions">
            <select id="instructionSelector">
                <option>{{__('iss::issNodePage.selectInstruction')}}</option>
            </select>
            <input type="button" id="loadInstruction" value="{{__('iss::issNodePage.loadInstruction')}}" />
            <input type="button" id="viewInstruction" value="{{__('iss::issNodePage.viewInstruction')}}" />
            <div id="instructionFrame"></div>
        </div>
        <div id="exam">
            <h2>{{__('iss::issNodePage.examHeader')}}</h2>
            <form action="" method="POST"> <!-- КОГДА СДЕЛАЮ КОНТРОЛЛЕР ДОБАВИТЬ МАРШРУТ -->
                @csrf
                @foreach ($nodeData['questions'] as $qName => $qText)
                <div class="mb-3">
                    <label class="form-label" for="{{$qName}}">{{$qText}}</label>
                    <input type="text" id="{{$qName}}" class="form-control myFormCorrectionInput"
                           name="{{$qName}}" placeholder="{{__('iss::issNodePage.enterAnswer')}}"
                           value="{{old($qName)}}" />
                    <div class="errorMsg">
                        @error($qName) {{__($message)}}  @enderror
                    </div>
                </div>
                @endforeach
                <div class="formButtonWrap">
                    <input type="reset" class="btn btn-primary" value="{{__('iss::issNodePage.resetExam')}}"/>
                    <input type="submit" class="btn btn-primary" value="{{__('iss::issNodePage.sendExam')}}"/>
                </div>
            </form>
        </div>
    </div>

    <div id="refBack">
        <p><a href="{{route('issUser', ['id' => $nodeData['userId']])}}">{{__('iss::issNodePage.refToUser')}}</a></p>
        <p><a href="{{route('main')}}">{{__('iss::issMainPage.refToMain')}}</a></p>
    </div>
@endsection
