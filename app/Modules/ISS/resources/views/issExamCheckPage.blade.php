@extends(config('iss.layout'))

@push('mainStyles')
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issExamCheckPageStyle.css')--}}"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{--asset('/css/iss/issSharedStyle.css')--}}"> -->
    @vite(['app/Modules/ISS/public/css/issSharedStyle.css'])
    @vite(['app/Modules/ISS/public/css/issExamCheckPageStyle.css'])
@endpush

@push('mainScripts')
    <script src="{{asset('js/iss/issExamCheckPage.js')}}"></script>
@endpush

@section('mainMenu')
    ___________________________________________
@endsection('mainMenu')


@section('content')
<div id="examCheck">
    <h2>{{__('iss::issExamCheckPage.header')}}</h2>
    <form id="examCheckForm" action="{{$signedRoute}}" method="POST">
        @csrf
        <div class="mb-3 correctInput">
            <label class="form-label" for="checkCode">{{__('iss::issExamCheckPage.checkCode')}}</label>
            <input type="text" id="checkCode" class="form-control myFormCorrectionInput"
                   name="examCheckCode" placeholder="{{__('iss::issExamCheckPage.examCheckCode')}}"
                   value="{{old('examCheckCode')}}" />

            <div class="errorMsg">
                @error('examCheckCode') {{__($message)}}  @enderror
            </div>
        </div>

        <div id="resultExam">
        <fieldset>
            <legend class="form-label">{{__('iss::issExamCheckPage.examCheckResultLabel')}}</legend>
            <div class="result">
                <input type="radio" id="passed" class="form-check-input"
                       name="examCheckResult" value="{{config('iss.EXAM_STATUS.passed')}}" />
                <label for="passed" class="form-check-label">
                    {{__('iss::issExamCheckPage.passed')}}
                </label>
                <hr>
            </div>
            <div class="result">
                <input type="radio" id="fail" class="form-check-input"
                       name="examCheckResult" value="{{config('iss.EXAM_STATUS.failed')}}" />
                <label for="fail" class="form-check-label">
                    {{__('iss::issExamCheckPage.fail')}}
                </label>
                <hr>
            </div>

            <div class="errorMsg">
                @error('examCheckResult') {{__($message)}}  @enderror
            </div>
        </fieldset>

        </div>
        <div class="mb-3 correctInput">
            <label class="form-label" for="comment">{{__('iss::issExamCheckPage.comment')}}</label>
            <input type="text" id="comment" class="form-control myFormCorrectionInput"
                   name="examComment" placeholder="{{__('iss::issExamCheckPage.examComment')}}"
                   value="{{old('examComment')}}" />

            <div class="errorMsg">
                @error('examComment') {{__($message)}}  @enderror
            </div>
        </div>

        <div class="formButtonWrap">
            <input type="reset" class="btn btn-primary" value="{{__('iss::issExamCheckPage.resetExamCheck')}}"/>
            <input type="submit" class="btn btn-primary" value="{{__('iss::issExamCheckPage.sendExamCheck')}}"/>
        </div>
    </form>


    @error('serviceError')
        <div id="serviceError">
            {{__($message)}}
        </div>
    @enderror

    @if($success)
        <div id="examChecked">
            {{__($success)}}
        </div>
    @endif
</div>
@endsection('content')
