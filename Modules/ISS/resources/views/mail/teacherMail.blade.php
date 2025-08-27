<!DOCTYPE HTML>
<html>
<head>
<style>
    caption { margin-bottom: 20px; }
</style>
</head>
<body>
<h2>TEACHER MAIL</h2>

<div id="examBlank">
    <table>
        <caption>{{__('iss::issNotify.teacherMail.examBlank')}}</caption>
        <tr>
            <th>{{__('iss::issNotify.teacherMail.questionText')}}</th>
            <th>{{__('iss::issNotify.teacherMail.AnswerText')}}</th>
            <th>{{__('iss::issNotify.teacherMail.RightAnswerText')}}</th>
        </tr>
    @foreach($checkedQuestions as $question)
        <tr>
        <td>{{$question->questionText}}</td>
        @if(!is_numeric($question->answerId))
                <td>{{$question->answerId}}</td>
        @else
                <td>{{$question->answerText}}</td>
        @endif
        <td>{{$question->rightAnswerText}}</td>
        </tr>
    @endforeach
    </table>
</div>
<hr>
<div id="checkCode">
    <h3>{{__('iss::issNotify.teacherMail.checkCode')}}</h3>
    <p>{{$checkCode}}</p>
</div>

<div id="signedUrl">
    <a href="{{$signedUrl}}">{{__('iss::issNotify.teacherMail.signedUrl')}}</a>
</div>
</body>
</html>



