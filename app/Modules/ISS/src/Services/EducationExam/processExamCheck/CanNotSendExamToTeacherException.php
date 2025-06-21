<?php

namespace App\Modules\ISS\src\Services\EducationExam\processExamCheck;

class CanNotSendExamToTeacherException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.canNotSendExamToTeacher');
    }
}
