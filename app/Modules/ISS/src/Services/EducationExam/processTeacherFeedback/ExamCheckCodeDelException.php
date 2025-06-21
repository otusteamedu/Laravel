<?php

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

class ExamCheckCodeDelException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.ExamCheckCodeDelException');
    }
}
