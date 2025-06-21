@extends(config('iss.layout'))


@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issNodePageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issSharedStyle.css'])
    @vite(['app/Modules/ISS/public/css/issNodePageStyle.css'])
    @vite(['app/Modules/ISS/public/css/components/iss-messages-Style.css'])
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
                ['routeName' => $pointData['routeName'], 'pointName' => $pointData['pointName']])}}</p>
            <h2>{{__('iss::issNodePage.examResult',
                ['result' => $pointData['examResult'], 'examDate' => $pointData['examDate']])}}</h2>
        </div>

        <div id="videoInstructions">
            <select id="instructionTypeSelectorVideo">
                <option>{{__('iss::issNodePage.selectInstructionType')}}</option>
                @foreach($pointData['videoFileTypes'] as $type)
                    <option>{{$type}}</option>
                @endforeach
            </select>
            <select id="videoSelector">
                <option initial value="">{{__('iss::issNodePage.selectVideo')}}</option>
                @foreach($pointData['videoFileTypes'] as $type)
                    @isset($pointData['materials'][$type])
                        @foreach($pointData['materials'][$type] as $title => $file)
                            <option hidden materialType="{{$type}}" value="{{$file}}">{{$title}}</option>
                        @endforeach
                    @endisset
                @endforeach
            </select>
            <input type="button" id="loadVideo" value="{{__('iss::issNodePage.loadVideo')}}" />
            <input type="button" id="viewVideo" value="{{__('iss::issNodePage.viewVideo')}}" />
        </div>

        <div id="textAndPdfInstructions">
            <select id="instructionTypeSelector">
                <option>{{__('iss::issNodePage.selectInstructionType')}}</option>
                @foreach($pointData['textFileTypes'] as $type)
                    <option>{{$type}}</option>
                @endforeach
            </select>
            <select id="instructionSelector">
                <option initial value="">{{__('iss::issNodePage.selectInstruction')}}</option>
                @foreach($pointData['textFileTypes'] as $type)
                    @isset($pointData['materials'][$type])
                        @foreach($pointData['materials'][$type] as $title => $file)
                            <option hidden materialType="{{$type}}" value="{{$file}}">{{$title}}</option>
                        @endforeach
                    @endisset
                @endforeach
            </select>
            <input type="button" id="loadInstruction" value="{{__('iss::issNodePage.loadInstruction')}}" />
            <input type="button" id="viewInstruction" value="{{__('iss::issNodePage.viewInstruction')}}" />
        </div>

        <div id="exam">
            <h2>{{__('iss::issNodePage.examHeader')}}</h2>
            <form id="checkExamForm" method="POST">
                @csrf
                <input type="hidden" name="issUserId" value="{{$pointData['userId']}}" />
                <input type="hidden" name="realEducationRoutePointId" value="{{$pointData['pointId']}}" />
                @foreach ($pointData['questions'] as $question)
                <div class="mb-3 correctQuestion">
                    @isset($question['answers'])
                        @empty($question['answers'])
                            <label class="form-label" for="{{$question['questionName']}}">{{$question['questionText']}}</label>
                            <input type="text" id="{{$question['questionName']}}" class="form-control myFormCorrectionInput"
                                   name="question_{{$question['questionId']}}" placeholder="{{__('iss::issNodePage.enterAnswer')}}"
                                   value="{{old('question_'.$question['questionId'])}}" />

                            <div class="errorMsg">
                                @error('question_'.$question['questionId']) {{__($message)}}  @enderror
                            </div>
                        @else
                            <fieldset>
                                <legend class="form-label">{{$question['questionText']}}</legend>
                                <input type="radio" hidden checked
                                       name="question_{{$question['questionId']}}" value="0" />
                            @foreach($question['answers'] as $answer)
                                    <div class="answer">
                                        <input type="radio" id="answer_{{$answer['id']}}" class="form-check-input"
                                               name="question_{{$question['questionId']}}" value="{{$answer['id']}}" />
                                        <label for="answer_{{$answer['id']}}" class="form-check-label">
                                            {{$answer['answer']}}
                                        </label>
                                        <hr>
                                    </div>
                            @endforeach
                            </fieldset>
                        @endempty
                    @else <div class="questionDamaged">{{__('iss::issNodePage.questionIsDamaged')}}</div>
                    @endisset

                </div>
                @endforeach
                <div class="formButtonWrap">
                    <input type="reset" class="btn btn-primary"
                           @if($pointData['examResult'] == config('iss.REAL_ROUTE_POINT_STATE.passed'))
                               disabled
                           @endif
                           value="{{__('iss::issNodePage.resetExam')}}"/>
                    <input type="button" id="submitExam" class="btn btn-primary"
                           @if($pointData['examResult'] == config('iss.REAL_ROUTE_POINT_STATE.passed'))
                               disabled
                           @endif
                           value="{{__('iss::issNodePage.sendExam')}}"/>
                </div>
            </form>
        </div>
    </div>

<!-- php $m='test';                        пример использования компонента -->
<!-- <x-iss-messages :issMessage="@$m" />  пример использования компонента -->

<div id="issMessage" hidden></div>

    <div id="refBack">
        @include('iss::blocks.refToUser', ['issUserId' => $pointData['userId']])
        @include('iss::blocks.refToMainISS')
        @include('iss::blocks.refToMainApp')
    </div>
@endsection
