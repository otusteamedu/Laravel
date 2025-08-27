<!DOCTYPE HTML>
<html>
<head></head>
<body>

<h2>STUDENT MAIL</h2>

<div id="examResultStatus">
    <p>{{__('iss::issNotify.studentMail.notification',
            ['route' => $routeName, 'point' => $pointName, 'status' => $examCheckResult, 'date' => $examDate])}}</p>
</div>

</body>
</html>
