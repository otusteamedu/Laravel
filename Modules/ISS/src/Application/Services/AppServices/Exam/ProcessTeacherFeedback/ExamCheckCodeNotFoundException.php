<?php

namespace ISS\App\Application\Services\AppServices\Exam\ProcessTeacherFeedback;

class ExamCheckCodeNotFoundException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = "Exam check code not found";
    }
}
