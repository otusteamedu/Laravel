@extends(config('iss.layout'))

@push('mainStyles')
    @vite(['Modules/ISS/public/css/adminInterface/issRoutePointCreateOrEditStyle.css'])
    @vite(['Modules/ISS/public/css/adminInterface/issSharedStyle.css'])
@endpush

@push('mainScripts')
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')

@section('content')
    @if($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.edit'))
        <h1>{{__('iss::issAdminPointCRUDInterface.pointEditFormLabel')}}</h1>
    @else
        <h1>{{__('iss::issAdminPointCRUDInterface.pointCreateFormLabel')}}</h1>
    @endif


    @include('iss::adminInterface.issSharedMessage')

    <form
        enctype="multipart/form-data"
        action="
            @if($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.edit'))
                  {{route('RoutePointManage.update', ['RoutePointManage' => $pointParameters['pointId']])}}
            @elseif($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.create'))
                  {{route('RoutePointManage.store')}}
            @endif"
    method="POST">
        @csrf

        @csrf
        @if($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.edit'))
            @method('put')
        @elseif($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.create'))
            @method('post')
        @endif

        <fieldset id="exam">
            @foreach($pointParameters['questions'] as $question)
                <div class="question">
                    <h3>{{__('iss::issAdminPointCRUDInterface.questionFormHeader')}}</h3>
                    <table>
                        <tr>
                            <td>{{__('iss::issAdminPointCRUDInterface.questionId')}}</td>
                            <td><input type="text" readonly name="questions[{{$question['num']}}][id]"
                                       value="{{$question['id']}}" /></td>
                        </tr>
                        <tr>
                            <td>{{__('iss::issAdminPointCRUDInterface.questionName')}}</td>
                            <td><input type="text" name="questions[{{$question['num']}}][questionName]"
                                       value="{{$question['questionName']}}" /></td>
                        </tr>
                        <tr>
                            <td>{{__('iss::issAdminPointCRUDInterface.questionText')}}</td>
                            <td><input type="text" name="questions[{{$question['num']}}][questionText]"
                                       value="{{$question['questionText']}}" /></td>
                        </tr>
                    </table>

                    <div class="answers">
                        <h3>{{__('iss::issAdminPointCRUDInterface.answerFormHeader')}}</h3>
                        @isset($question['answers'])
                        @foreach($question['answers'] as $answer)
                            <div class="singleAnswer">
                                <table>
                                    <tr>
                                        <td>{{__('iss::issAdminPointCRUDInterface.answerId')}}</td>
                                        <td><input type="text" readonly
                                                   name="questions[{{$question['num']}}][answers][{{$answer['num']}}][id]"
                                                   value="{{$answer['id']}}" /></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('iss::issAdminPointCRUDInterface.answerName')}}</td>
                                        <td> <input type="text"
                                                    name="questions[{{$question['num']}}][answers][{{$answer['num']}}][answerName]"
                                                    value="{{$answer['answerName']}}" /></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('iss::issAdminPointCRUDInterface.answerText')}}</td>
                                        <td><input type="text"
                                                   name="questions[{{$question['num']}}][answers][{{$answer['num']}}][answerText]"
                                                   value="{{$answer['answerText']}}" /></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('iss::issAdminPointCRUDInterface.answerQuestionId')}}</td>
                                        <td><input type="text" readonly
                                                   name="questions[{{$question['num']}}][answers][{{$answer['num']}}][questionId]"
                                                   value="{{$answer['questionId']}}" /></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('iss::issAdminPointCRUDInterface.answerIsRight')}}</td>
                                        <td><input type="text"
                                                   name="questions[{{$question['num']}}][answers][{{$answer['num']}}][isRight]"
                                                   value="{{$answer['isRight']}}" /></td>
                                    </tr>
                                </table>
                                <input type="button" class="myButton fontRed"
                                       value="{{__('iss::issAdminPointCRUDInterface.delCurrentAnswer')}}" />
                            </div>
                        @endforeach
                        @endisset
                            <input type="button" class="myButton fontGreen"
                                   value="{{__('iss::issAdminPointCRUDInterface.addAnswer')}}" />
                    </div>
                    <input type="button" class="myButton fontRed"
                           value="{{__('iss::issAdminPointCRUDInterface.delCurrentQuestion')}}" />
                </div>
            @endforeach
            <input type="button" id="addQuestion" class="myButton fontGreen"
                value="{{__('iss::issAdminPointCRUDInterface.addQuestion')}}" />
        </fieldset>

        <!-- ДОДЕЛАТЬ ФОРМУ ДЛЯ УЧЕБНЫХ МАТЕРИАЛОВ + JS ДЛЯ ОЖИВЛЕНИЯ ФОРМЫ -->
        <fieldset id="videoInstructions" style="border: 1px solid blue">
            <input type="text" name="videoMaterial[0][id]" value="0" />
            <input type="text" name="videoMaterial[0][title]" value="0" />
            <input type="text" name="videoMaterial[0][typeId]" value="0" />
            <input type="text" name="videoMaterial[0][filePath]" value="\example\file\path" />
        </fieldset>

        <fieldset id="textInstructions" style="border: 1px solid green">
            <input type="text" name="textMaterial[0][id]" value="0" />
            <input type="text" name="textMaterial[0][typeId]" value="0" />
            <input type="text" name="textMaterial[0][filePath]" value="\example\file\path" />
        </fieldset>

        <input type="submit" value="@if($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.edit')){{__('iss::issAdminPointCRUDInterface.pointEdit')}}@elseif($action === config('iss.ISS_REF_ROUTE_POINT_ACTION.create')){{__('iss::issAdminPointCRUDInterface.pointCreate')}}@endif" />
    </form>

    <div id="refBack"><a href="{{route('RoutePointManage.index')}}">{{__('iss::issAdminPointCRUDInterface.refToIssPointList')}}</a></div>

@endsection('content')
