<?php

return [
    'examPassed' => 'Exam passed',
    'examFailed' => 'Exam failed',
    'examWrongStatus' => 'Exam status error',
    'teacherComment' => 'teacher comment: ',
    'examSentToTeacher' => 'Exam sent to teacher',
    'examStatusNotify' => [
        'mailHeader' => 'ISS Exam Status Notify',
    ],
    'teacherMail' => [
        'mailHeader' => 'ISS Teacher Mail',
        'fromName' => 'ISS System',
        'signedUrl' => 'Come to this link to send exam check results',
        'checkCode' => 'This is your individual one-use check code. Enter it in check form, to send exam check results',
        'examBlank' => 'Exam blank for check',
        'questionText' => 'Question',
        'AnswerText' => 'Answer',
        'RightAnswerText' => 'Right answer',
    ],
    'studentMail' => [
        'fromName' => 'ISS System',
        'notification' => 'Dear student, notify you that exam for
                           education route :route on stage :point has status :status (exam date on schedule :date)',
    ],
];
