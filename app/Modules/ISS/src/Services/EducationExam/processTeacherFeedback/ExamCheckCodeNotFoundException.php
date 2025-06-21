<?php

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

class ExamCheckCodeNotFoundException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = "Exam check code not found";
    }
}
