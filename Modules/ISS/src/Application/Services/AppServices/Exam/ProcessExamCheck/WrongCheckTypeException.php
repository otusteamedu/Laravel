<?php

namespace ISS\App\Application\Services\AppServices\Exam\ProcessExamCheck;

class WrongCheckTypeException extends \Exception
{
    public $message;

    public function __construct()
    {
        parent::__construct();
        $this->message = __('iss::issExceptions.wrongCheckType');
    }
}
