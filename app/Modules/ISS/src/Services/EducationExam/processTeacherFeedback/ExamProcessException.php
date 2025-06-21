<?php

namespace App\Modules\ISS\src\Services\EducationExam\processTeacherFeedback;

class ExamProcessException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.ExamProcessException');
    }
}
