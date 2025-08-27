<?php

namespace ISS\App\Application\Services\AppServices\Exam\ProcessTeacherFeedback;

class ExamProcessException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.ExamProcessException');
    }
}
