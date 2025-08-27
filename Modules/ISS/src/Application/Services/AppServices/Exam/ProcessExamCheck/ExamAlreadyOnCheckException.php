<?php

namespace ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck;

class ExamAlreadyOnCheckException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.examAlreadyOnCheck');
    }
}
