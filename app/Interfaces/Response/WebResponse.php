<?php

namespace App\Interfaces\Response;

class WebResponse
{
    public $success;
    public $data;
    public $message;
    public $errors;
    public $statusCode;

    public function __construct(
        bool $success,
        $data = null,
        string $message = '',
        array $errors = [],
        int $statusCode = 200
    ) {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    public function toArray()
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'errors' => $this->errors,
            'status_code' => $this->statusCode,
        ];
    }
}